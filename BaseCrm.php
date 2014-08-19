<?php

namespace prgTW\BaseCRM;

use prgTW\BaseCRM\Client\ClientInterface;
use prgTW\BaseCRM\Client\GuzzleClient;
use prgTW\BaseCRM\Resource\Resource;
use prgTW\BaseCRM\Service\Account;
use prgTW\BaseCRM\Service\Sources;

/**
 * @property Account account
 * @property Sources sources
 */
class BaseCrm extends Resource
{
	/** @var GuzzleClient */
	protected $client;

	/**
	 * @param string          $token
	 * @param ClientInterface $client
	 */
	public function __construct($token, ClientInterface $client = null)
	{
		$this->client = null !== $client ? $client : new GuzzleClient($token);
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
