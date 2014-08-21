<?php

namespace prgTW\BaseCRM\Tests\Service;

use prgTW\BaseCRM\BaseCrm;
use prgTW\BaseCRM\Client\GuzzleClient;
use prgTW\BaseCRM\Resource\Resource;
use prgTW\BaseCRM\Tests\AbstractTest;
use prgTW\BaseCRM\Utils\Currency;

class AccountTest extends AbstractTest
{
	public function testGet()
	{
		$client  = \Mockery::mock(GuzzleClient::class)
			->shouldReceive('request')
			->once()
			->with('GET', sprintf('%s/%s/account.json', Resource::ENDPOINT_SALES, Resource::PREFIX), \Mockery::any())
			->andReturn($this->getResponse(200, '
				{
					"account": {
						"id": 123,
						"name": "myaccount",
						"timezone": "UTC",
						"currency_name": "US Dollar"
					}
				}
			'))
			->getMock();
		$baseCrm = new BaseCrm('', $client);

		$account = $baseCrm->getAccount();
		$this->assertEquals('myaccount', $account->getName());
	}

	public function testCurrencyAlteration()
	{
		$client  = \Mockery::mock(GuzzleClient::class)
			->shouldReceive('request')
			->once()
			->with('GET', sprintf('%s/%s/account.json', Resource::ENDPOINT_SALES, Resource::PREFIX), \Mockery::any())
			->andReturn($this->getResponse(200, '
				{
					"account": {
						"id": 123,
						"name": "myaccount",
						"timezone": "UTC",
						"currency_name": "US Dollar"
					}
				}
			'))
			->getMock();
		$baseCrm = new BaseCrm('', $client);

		$account = $baseCrm->getAccount();
		$this->assertEquals(Currency::USD(), $account->getCurrency());
		$account->setCurrency(Currency::PLN());

		$client
			->shouldReceive('request')
			->once()
			->with('PUT', sprintf('%s/%s/account.json', Resource::ENDPOINT_SALES, Resource::PREFIX), $this->getQuery([
				'query' => [
					'account' => [
						'id'          => 123,
						'name'        => 'myaccount',
						'currency_id' => Currency::PLN,
						'timezone'    => 'UTC',
					]
				],
			]))
			->andReturn($this->getResponse(200, '
				{
					"account": {
						"id": 123,
						"name": "myaccount",
						"timezone": "UTC",
						"currency_name": "Polish złoty"
					}
				}
			'));

		$account->save();
		$this->assertEquals(Currency::PLN()->getName(), $account->getCurrency()->getName());
	}
}
