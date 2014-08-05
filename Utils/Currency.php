<?php

namespace prgTW\BaseCRM\Utils;

use MyCLabs\Enum\Enum;

/**
 * @method static Currency USD()
 * @method static Currency GBP()
 * @method static Currency EUR()
 * @method static Currency JPY()
 * @method static Currency CAD()
 * @method static Currency AUD()
 * @method static Currency ZAR()
 * @method static Currency PLN()
 * @method static Currency DKK()
 * @method static Currency NZD()
 * @method static Currency INR()
 */
class Currency extends Enum
{
	const USD = 1;
	const GBP = 2;
	const EUR = 3;
	const JPY = 4;
	const CAD = 5;
	const AUD = 6;
	const ZAR = 7;
	const PLN = 8;
	const DKK = 9;
	const NZD = 10;
	const INR = 11;

	protected static $names = [
		self::USD => 'US Dollar',
		self::GBP => 'British Pound',
		self::EUR => 'Euro',
		self::JPY => 'Yen',
		self::CAD => 'Canadian Dollar',
		self::AUD => 'Australian Dollar',
		self::ZAR => 'South African Rand',
		self::PLN => 'Polish złoty',
		self::DKK => 'Danish Kroner',
		self::NZD => 'New Zealand Dollar',
		self::INR => 'Indian Rupee',
	];

	/**
	 * @return string
	 */
	public function getName()
	{
		return self::$names[$this->value];
	}

	/**
	 * @param string $name
	 *
	 * @return Currency
	 * @throws \InvalidArgumentException when invalid currency name given
	 */
	public static function fromName($name)
	{
		$key = array_search($name, self::$names);
		if (false === $key)
		{
			throw new \InvalidArgumentException('Invalid currency name given');
		}

		return new self($key);
	}

	/**
	 * @return array
	 */
	public static function getNames()
	{
		return static::$names;
	}
}
