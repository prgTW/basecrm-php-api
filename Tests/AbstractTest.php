<?php

namespace prgTW\BaseCRM\Tests;

abstract class AbstractTest extends \PHPUnit_Framework_TestCase
{
	/** {@inheritdoc} */
	public function tearDown()
	{
		\Mockery::close();
	}
}
