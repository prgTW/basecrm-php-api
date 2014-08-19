<?php

namespace prgTW\BaseCRM\Tests;

use prgTW\BaseCRM\BaseCrm;

abstract class AbstractTest extends \PHPUnit_Framework_TestCase
{
	/** @var BaseCrm */
	protected $baseCrm;

	/**
	 * @return string
	 */
	public static function getToken()
	{
		return getenv('BASECRM_TOKEN');
	}

	public function setUp()
	{
		$this->baseCrm = new BaseCrm(self::getToken());
	}
}
