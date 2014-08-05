<?php

namespace prgTW\BaseCRM\Utils;

class Inflector
{
	/**
	 * @param string $word
	 *
	 * @return string
	 */
	public static function classify($word)
	{
		return str_replace(' ', '', ucwords(strtr($word, '_-', '  ')));
	}

	/**
	 * @param string $word
	 *
	 * @return string
	 */
	public static function camelize($word)
	{
		return lcfirst(self::classify($word));
	}

	/**
	 * @param string $word
	 *
	 * @return string
	 */
	public static function underscore($word)
	{
		return strtolower(preg_replace('~(?<=\\w)([A-Z])~', '_$1', $word));
	}
}
