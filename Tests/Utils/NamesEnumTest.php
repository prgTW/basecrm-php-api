<?php

namespace prgTW\BaseCRM\Tests\Utils;

use prgTW\BaseCRM\Service\Enum\Currency;
use prgTW\BaseCRM\Service\Enum\NamedEnum;
use prgTW\BaseCRM\Service\Enum\TagAppId;

class NamesEnumTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @dataProvider provideEnumClasses
	 * @expectedException \InvalidArgumentException
	 *
	 * @param string $enumClass
	 */
	public function testNonExistingEnumValue($enumClass)
	{
		call_user_func_array(sprintf('%s::fromName', $enumClass), ['non_existing']);
	}

	/**
	 * @dataProvider provideEnumClasses
	 *
	 * @param string $enumClass
	 */
	public function testEnumCoverage($enumClass)
	{
		$values = call_user_func_array(sprintf('%s::toArray', $enumClass), []);
		$names  = call_user_func_array(sprintf('%s::getNames', $enumClass), []);
		$this->assertEquals(count($values), count($names));
		$this->assertEquals(array_values($values), array_keys($names));
	}

	/**
	 * @return array
	 */
	public function provideEnumClasses()
	{
		return [
			[Currency::class],
			[TagAppId::class],
		];
	}

	/**
	 * @dataProvider provideMappings
	 *
	 * @param string $enumClass
	 * @param mixed  $value
	 * @param string $abbr
	 * @param mixed  $name
	 */
	public function testMapping($enumClass, $value, $abbr, $name)
	{
		/** @var NamedEnum $namedEnum */
		$namedEnum = call_user_func_array(sprintf('%s::%s', $enumClass, $abbr), []);
		$this->assertEquals($name, $namedEnum->getName());
		$this->assertEquals($value, $namedEnum->getValue());

		/** @var NamedEnum $namedEnum */
		$namedEnum = new $enumClass($value);
		$this->assertEquals($value, $namedEnum->getValue());

		/** @var NamedEnum $enum */
		$namedEnum = call_user_func_array(sprintf('%s::fromName', $enumClass), [$name]);
		$this->assertEquals($value, $namedEnum->getValue());
	}

	/**
	 * @return array
	 */
	public function provideMappings()
	{
		return [
			[Currency::class, 1, 'USD', 'US Dollar'],
			[Currency::class, 2, 'GBP', 'British Pound'],
			[Currency::class, 3, 'EUR', 'Euro'],
			[Currency::class, 4, 'JPY', 'Yen'],
			[Currency::class, 5, 'CAD', 'Canadian Dollar'],
			[Currency::class, 6, 'AUD', 'Australian Dollar'],
			[Currency::class, 7, 'ZAR', 'South African Rand'],
			[Currency::class, 8, 'PLN', 'Polish złoty'],
			[Currency::class, 9, 'DKK', 'Danish Kroner'],
			[Currency::class, 10, 'NZD', 'New Zealand Dollar'],
			[Currency::class, 11, 'INR', 'Indian Rupee'],
			[TagAppId::class, 1, 'DEALS', 'Deal'],
			[TagAppId::class, 4, 'CONTACTS', 'Contact'],
			[TagAppId::class, 5, 'LEADS', 'Lead'],
		];
	}
}
