<?php

namespace prgTW\BaseCRM;

use prgTW\BaseCRM\Client\ClientInterface;
use prgTW\BaseCRM\Resource\Resource;
use prgTW\BaseCRM\Service\Account;
use prgTW\BaseCRM\Service\Leads;
use prgTW\BaseCRM\Service\Sources;
use prgTW\BaseCRM\Transport\Transport;

/**
 * @method Account getAccount()
 * @method Sources getSources()
 * @method Leads getLeads()
 */
class BaseCrm extends Resource
{
	/** @var Transport */
	protected $transport;

	/**
	 * @param string          $token
	 * @param ClientInterface $client
	 */
	public function __construct($token, ClientInterface $client = null)
	{
		$this->transport = new Transport($token, $client);
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
