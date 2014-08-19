<?php

namespace prgTW\BaseCRM;

use prgTW\BaseCRM\Client\GuzzleClient;
use prgTW\BaseCRM\Resource\Resource;
use prgTW\BaseCRM\Service\Rest\Account;
use prgTW\BaseCRM\Service\Rest\Sources;

/**
 * @property Account account
 * @property Sources sources
 */
class BaseCrm extends Resource
{
	/** @var GuzzleClient */
	protected $client;

	/**
	 * @param string $token
	 */
	public function __construct($token)
	{
		$this->client = new GuzzleClient($token);
		$this->setSubResources([
			Account::class,
			Sources::class,
		]);
	}

	/** {@inheritdoc} */
	protected function getEndpoint()
	{
		throw new \LogicException('Cannot call baseCrm directly');
	}
}
