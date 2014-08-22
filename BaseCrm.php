<?php

namespace prgTW\BaseCRM;

use prgTW\BaseCRM\Client\ClientInterface;
use prgTW\BaseCRM\Resource\Resource;
use prgTW\BaseCRM\Service\Account;
use prgTW\BaseCRM\Service\Contacts;
use prgTW\BaseCRM\Service\Leads;
use prgTW\BaseCRM\Service\Sources;
use prgTW\BaseCRM\Service\Tags;
use prgTW\BaseCRM\Transport\Transport;

/**
 * @method Account getAccount()
 * @method Contacts getContacts()
 * @method Sources getSources()
 * @method Leads getLeads()
 * @method Tags getTaggings()
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
		$transport = new Transport($token, $client);
		parent::__construct($transport, '');
	}

	/** {@inheritdoc} */
	protected function init()
	{
		$this->setSubResources([
			Account::class,
			Contacts::class,
			Sources::class,
			Leads::class,
			Tags::class,
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
