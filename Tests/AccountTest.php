<?php

namespace prgTW\BaseCRM\Tests;

use prgTW\BaseCRM\BaseCrm;
use prgTW\BaseCRM\Client\Client;
use prgTW\BaseCRM\Resource\Resource;
use prgTW\BaseCRM\Service\Account;
use prgTW\BaseCRM\Utils\Currency;

class AccountTest extends AbstractTest
{
	public function testAccountGet()
	{
		$client  = \Mockery::mock(Client::class)
			->shouldReceive('get')
			->once()
			->with(sprintf('%s/%s/account', Resource::ENDPOINT_SALES, Resource::PREFIX), 'account')
			->andReturn([
				'id'            => 123,
				'name'          => 'abrakadabra',
				'currency_name' => Currency::getNames()[Currency::EUR],
			])
			->getMock();
		$baseCrm = new BaseCrm('', $client);

		$account = $baseCrm->getAccount();
		$this->assertInstanceOf(Account::class, $account);
		$this->assertEquals('abrakadabra', $account->getName());
	}

	public function testCurrencyAlteration()
	{
		$client  = \Mockery::mock(Client::class)
			->shouldReceive('get')
			->once()
			->with(sprintf('%s/%s/account', Resource::ENDPOINT_SALES, Resource::PREFIX), 'account')
			->andReturn([
				'id'            => 123,
				'name'          => 'abrakadabra',
				'currency_name' => Currency::getNames()[Currency::EUR],
			])
			->getMock();
		$baseCrm = new BaseCrm('', $client);

		$account = $baseCrm->getAccount();
		$this->assertEquals(Currency::EUR(), $account->getCurrency());
		$account->setCurrency(Currency::PLN());

		$client
			->shouldReceive('put')
			->once()
			->with(sprintf('%s/%s/account', Resource::ENDPOINT_SALES, Resource::PREFIX), 'account', [
				'query' => [
					'account' => [
						'id'          => 123,
						'name'        => 'abrakadabra',
						'currency_id' => Currency::PLN,
						'timezone'    => null,
					]
				],
			])
			->andReturn([
				'id'            => 123,
				'name'          => 'abrakadabra',
				'currency_name' => Currency::getNames()[Currency::PLN],
			]);

		$account->save();
	}
}
