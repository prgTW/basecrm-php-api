<?php

namespace prgTW\BaseCRM\Utils;

class Convert
{
	/**
	 * @param mixed $obj
	 *
	 * @return array
	 */
	public static function objectToArray($obj)
	{
		if (is_object($obj))
		{
			$obj = get_object_vars($obj);
		}

		if (is_array($obj))
		{
			return array_map('self::objectToArray', $obj);
		}
		else
		{
			return $obj;
		}
	}

	/**
	 * @param mixed $array
	 *
	 * @return \StdClass
	 */
	public static function arrayToObject($array)
	{
		if (is_array($array))
		{
			return (object)array_map('self::arrayToObject', $array);
		}
		else
		{
			return $array;
		}
	}
}
