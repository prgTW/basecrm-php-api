<?php

namespace prgTW\BaseCRM\Service\Enum;

use MyCLabs\Enum\Enum;

abstract class NamedEnum extends Enum
{
	/** @var array */
	protected static $names = [];

	/**
	 * @return string
	 */
	public function getName()
	{
		return static::$names[$this->value];
	}

	/**
	 * @param string $name
	 *
	 * @return NamedEnum
	 * @throws \InvalidArgumentException when invalid currency name given
	 */
	public static function fromName($name)
	{
		$key = array_search($name, static::$names);
		if (false === $key)
		{
			throw new \InvalidArgumentException('Invalid currency name given');
		}

		return new static($key);
	}

	/**
	 * @return array
	 */
	public static function getNames()
	{
		return static::$names;
	}
}
