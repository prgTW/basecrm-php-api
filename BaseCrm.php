<?php

namespace prgTW\BaseCRM;

use prgTW\BaseCRM\Client\Client;
use prgTW\BaseCRM\Client\GuzzleClient;
use prgTW\BaseCRM\Resource\Resource;
use prgTW\BaseCRM\Service\Account;
use prgTW\BaseCRM\Service\Leads;
use prgTW\BaseCRM\Service\Sources;

/**
 * @method Account getAccount()
 * @method Sources getSources()
 * @method Leads getLeads()
 */
class BaseCrm extends Resource
{
	/** @var GuzzleClient */
	protected $client;

	/**
	 * @param string $token
	 * @param Client $client
	 */
	public function __construct($token, Client $client = null)
	{
		$this->client = null !== $client ? $client : new GuzzleClient($token);
		$this->setSubResources([
			Account::class,
			Sources::class,
			Leads::class,
		]);
	}

	/** {@inheritdoc} */
	protected function getEndpoint()
	{
		//@codeCoverageIgnoreStart
		throw new \LogicException('Cannot call baseCrm directly');
		//@codeCoverageIgnoreEnd
	}
}
