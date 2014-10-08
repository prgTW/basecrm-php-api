<?php

namespace prgTW\BaseCRM\Tests\Utils;

use prgTW\BaseCRM\Utils\Convert;

class ConvertTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @dataProvider provideObjectsAndArrays
	 *
	 * @param object $object
	 * @param array  $array
	 */
	public function testObjectToArray($object, array $array)
	{
		$converted = Convert::objectToArray($object);
		$this->assertEquals($array, $converted);
	}

	public function provideObjectsAndArrays()
	{
		$obj1          = new \stdClass();
		$obj1->a       = 1;
		$obj1->b       = new \stdClass();
		$obj1->b->c    = 2;
		$obj1->b->d    = new \stdClass();
		$obj1->b->d->e = 3;

		return [
			[new \stdClass(), []],
			[$obj1, ['a' => 1, 'b' => ['c' => 2, 'd' => ['e' => 3]]]],
		];
	}

}
