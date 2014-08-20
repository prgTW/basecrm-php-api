<?php

namespace prgTW\BaseCRM\Tests\Utils;

use prgTW\BaseCRM\Tests\AbstractTest;
use prgTW\BaseCRM\Utils\Currency;

class CurrencyTest extends AbstractTest
{
	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function testNonExistingCurrency()
	{
		Currency::fromName('non_existing');
	}

	public function testEnumCoverage()
	{
		$currencies = Currency::toArray();
		$names      = Currency::getNames();
		$this->assertEquals(count($currencies), count($names));
		$this->assertEquals(array_values($currencies), array_keys($names));
	}

	/**
	 * @dataProvider provideMappings
	 */
	public function testMapping($value, $abbr, $name)
	{
		$this->assertEquals($name, Currency::$abbr()->getName());
		$this->assertEquals($value, (new Currency($value))->getValue());
		$this->assertEquals($value, Currency::$abbr()->getValue());
		$this->assertEquals($value, Currency::fromName($name)->getValue());
	}

	/**
	 * @return array
	 */
	public function provideMappings()
	{
		return [
			[1, 'USD', 'US Dollar'],
			[2, 'GBP', 'British Pound'],
			[3, 'EUR', 'Euro'],
			[4, 'JPY', 'Yen'],
			[5, 'CAD', 'Canadian Dollar'],
			[6, 'AUD', 'Australian Dollar'],
			[7, 'ZAR', 'South African Rand'],
			[8, 'PLN', 'Polish złoty'],
			[9, 'DKK', 'Danish Kroner'],
			[10, 'NZD', 'New Zealand Dollar'],
			[11, 'INR', 'Indian Rupee'],
		];
	}
}
