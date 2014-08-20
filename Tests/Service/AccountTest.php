<?php

namespace prgTW\BaseCRM\Tests\Service;

use prgTW\BaseCRM\BaseCrm;
use prgTW\BaseCRM\Client\Client;
use prgTW\BaseCRM\Resource\Resource;
use prgTW\BaseCRM\Tests\AbstractTest;
use prgTW\BaseCRM\Utils\Currency;

class AccountTest extends AbstractTest
{
	public function testGet()
	{
		$client  = \Mockery::mock(Client::class)
			->shouldReceive('get')
			->once()
			->with(sprintf('%s/%s/account', Resource::ENDPOINT_SALES, Resource::PREFIX), 'account')
			->andReturn([
				'id'            => 123,
				'name'          => 'myaccount',
				'timezone'      => 'UTC',
				'currency_name' => 'US Dollar'
			])
			->getMock();
		$baseCrm = new BaseCrm('', $client);

		$account = $baseCrm->getAccount();
		$this->assertEquals('myaccount', $account->getName());
	}

	public function testCurrencyAlteration()
	{
		$client  = \Mockery::mock(Client::class)
			->shouldReceive('get')
			->once()
			->with(sprintf('%s/%s/account', Resource::ENDPOINT_SALES, Resource::PREFIX), 'account')
			->andReturn([
				'id'            => 123,
				'name'          => 'myaccount',
				'timezone'      => 'UTC',
				'currency_name' => 'US Dollar'
			])
			->getMock();
		$baseCrm = new BaseCrm('', $client);

		$account = $baseCrm->getAccount();
		$this->assertEquals(Currency::USD(), $account->getCurrency());
		$account->setCurrency(Currency::PLN());

		$client
			->shouldReceive('put')
			->once()
			->with(sprintf('%s/%s/account', Resource::ENDPOINT_SALES, Resource::PREFIX), 'account', [
				'query' => [
					'account' => [
						'id'          => 123,
						'name'        => 'myaccount',
						'currency_id' => Currency::PLN,
						'timezone'    => 'UTC',
					]
				],
			])
			->andReturn([
				'id'            => 123,
				'name'          => 'myaccount',
				'currency_name' => Currency::PLN()->getName(),
				'timezone'    => 'UTC',
			]);

		$account->save();
		$this->assertEquals(Currency::PLN()->getName(), $account->getCurrency()->getName());
	}
}
