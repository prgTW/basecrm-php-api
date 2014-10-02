<?php

namespace prgTW\BaseCRM\Tests\Transport;

use prgTW\BaseCRM\BaseCrm;
use prgTW\BaseCRM\Client\GuzzleClient;
use prgTW\BaseCRM\Resource\Resource;
use prgTW\BaseCRM\Tests\AbstractTest;
use prgTW\BaseCRM\Transport\Transport;

class TransportTest extends AbstractTest
{
	public function testChangingToken()
	{
		$accountResponse = '
			{
				"account": {
					"id": 123,
					"name": "myaccount",
					"timezone": "UTC",
					"currency_name": "US Dollar"
				}
			}
		';

		$client = \Mockery::mock(GuzzleClient::class);
		$client
			->shouldReceive('request')
			->once()
			->with('GET', sprintf('%s/%s/account.json', Resource::ENDPOINT_SALES, Resource::PREFIX), [
				'headers' => [
					Transport::TOKEN_PIPEJUMP_NAME     => '',
					Transport::TOKEN_FUTUERSIMPLE_NAME => '',
				],
			])
			->andReturn($this->getResponse(200, $accountResponse));
		$baseCrm = new BaseCrm('', $client);

		$baseCrm->getAccount()->get();

		$client
			->shouldReceive('request')
			->once()
			->with('GET', sprintf('%s/%s/account.json', Resource::ENDPOINT_SALES, Resource::PREFIX), [
				'headers' => [
					Transport::TOKEN_PIPEJUMP_NAME     => 'astalavista',
					Transport::TOKEN_FUTUERSIMPLE_NAME => 'astalavista',
				],
			])
			->andReturn($this->getResponse(200, $accountResponse));

		$baseCrm->setToken('astalavista');
		$this->assertEquals('astalavista', $baseCrm->getToken());
		$account = $baseCrm->getAccount()->get();

		$client
			->shouldReceive('request')
			->once()
			->with('PUT', sprintf('%s/%s/account.json', Resource::ENDPOINT_SALES, Resource::PREFIX), [
				'headers' => [
					Transport::TOKEN_PIPEJUMP_NAME     => 'baby',
					Transport::TOKEN_FUTUERSIMPLE_NAME => 'baby',
				],
				'query' => [
					'account' => [
						'name' => 'new_name',
					],
				],
			])
			->andReturn($this->getResponse(200, $accountResponse));

		$account->name = 'new_name';
		$account->getTransport()->setToken('baby');
		$this->assertEquals('baby', $account->getTransport()->getToken());
		$account->save(['name']);
	}
}
