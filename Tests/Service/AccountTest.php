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
		$client = \Mockery::mock(GuzzleClient::class);
		$client
			->shouldReceive('request')
			->once()
			->with('GET', sprintf('%s/%s/account.json', Resource::ENDPOINT_SALES, Resource::PREFIX), $this->getQuery())
			->andReturn($this->getResponse(200, '
				{
					"account": {
						"id": 123,
						"name": "myaccount",
						"timezone": "UTC",
						"currency_name": "US Dollar"
					}
				}
			'));
		$baseCrm = new BaseCrm('', $client);

		$account = $baseCrm->getAccount();
		$this->assertEquals('myaccount', $account->getName());
		$this->assertEquals('UTC', $account->getTimezone());
		$this->assertEquals(Currency::USD(), $account->getCurrency());
	}

	public function testCurrencyAlteration()
	{
		$client = \Mockery::mock(GuzzleClient::class);
		$client
			->shouldReceive('request')
			->once()
			->with('GET', sprintf('%s/%s/account.json', Resource::ENDPOINT_SALES, Resource::PREFIX), $this->getQuery())
			->andReturn($this->getResponse(200, '
				{
					"account": {
						"id": 123,
						"name": "myaccount",
						"timezone": "UTC",
						"currency_name": "US Dollar"
					}
				}
			'));
		$baseCrm = new BaseCrm('', $client);

		$account = $baseCrm->getAccount();

		$this->assertEquals(123, $account->getId());
		$this->assertEquals('myaccount', $account->getName());
		$this->assertEquals('UTC', $account->getTimezone());
		$this->assertEquals(Currency::USD(), $account->getCurrency());

		$client
			->shouldReceive('request')
			->once()
			->with('PUT', sprintf('%s/%s/account.json', Resource::ENDPOINT_SALES, Resource::PREFIX), $this->getQuery([
				'query' => [
					'account' => [
						'name'        => 'newname',
						'currency_id' => Currency::PLN,
						'timezone'    => 'new_timezone',
					]
				],
			]))
			->andReturn($this->getResponse(200, '
				{
					"account": {
						"id": 123,
						"name": "newname",
						"timezone": "new_timezone",
						"currency_name": "Polish złoty"
					}
				}
			'));

		$account->setName('newname');
		$account->setTimezone('new_timezone');
		$account->setCurrency(Currency::PLN());
		$account->save();

		$this->assertEquals(123, $account->getId());
		$this->assertEquals('newname', $account->getName());
		$this->assertEquals('new_timezone', $account->getTimezone());
		$this->assertEquals(Currency::PLN(), $account->getCurrency());
	}
}
