<?php

namespace prgTW\BaseCRM\Tests;

abstract class AbstractTest extends \PHPUnit_Framework_TestCase
{
	public function tearDown()
	{
		\Mockery::close();
	}
}
