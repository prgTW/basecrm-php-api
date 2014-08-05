<?php

namespace prgTW\BaseCRM\Tests;

use prgTW\BaseCRM\Client;

abstract class AbstractTest extends \PHPUnit_Framework_TestCase
{
	/** @var Client */
	protected $client;

	/**
	 * @return string
	 */
	public static function getToken()
	{
		return getenv('BASECRM_TOKEN');
	}

	public function setUp()
	{
		$this->client = new \prgTW\BaseCRM\Client(self::getToken());
	}
}
