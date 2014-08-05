<?php

namespace prgTW\BaseCRM\Tests;

use prgTW\BaseCRM\Service\Rest\Account;
use prgTW\BaseCRM\Utils\Currency;

class AccountTest extends AbstractTest
{
	public function testAccountGet()
	{
		$account = $this->client->account;
		$this->assertInstanceOf(Account::class, $account);
		$this->assertEquals('mateuszmikulski', $account->getName());
	}

	public function testCurrencyAlteration()
	{
		$account = $this->client->account;

		$this->assertNotEquals(Currency::PLN, $account->getCurrency());
		$account->setCurrency(Currency::PLN());
		$account->save();
	}
}
