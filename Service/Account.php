<?php

namespace prgTW\BaseCRM\Service;

use prgTW\BaseCRM\Resource\InstanceResource;
use prgTW\BaseCRM\Utils\Currency;

/**
 * @property-read int      id
 * @property string        name
 * @property string        timezone
 * @property-read Currency currency
 */
class Account extends InstanceResource
{
	/** {@inheritdoc} */
	protected function getEndpoint()
	{
		return self::ENDPOINT_SALES;
	}

	/**
	 * @return int
	 */
	protected function getId()
	{
		$this->getIfNecessary();

		return $this->data['id'];
	}

	/**
	 * @param Currency $currency
	 *
	 * @return $this
	 */
	public function setCurrency(Currency $currency)
	{
		$this->data['currency_id']   = $currency->getValue();
		$this->data['currency_name'] = $currency->getName();

		return $this;
	}

	/**
	 * @return Currency
	 */
	protected function getCurrency()
	{
		$this->getIfNecessary();

		return new Currency($this->data['currency_id']);
	}

	/**
	 * @return string
	 */
	protected function getName()
	{
		$this->getIfNecessary();

		return $this->data['name'];
	}

	/**
	 * @return string
	 */
	protected function getTimezone()
	{
		$this->getIfNecessary();

		return $this->data['timezone'];
	}

	/** {@inheritdoc} */
	protected function postHydrate(array $data)
	{
		$this->currency = Currency::fromName($data['currency_name']);
	}

	/** {@inheritdoc} */
	protected function postDehydrate(array &$data)
	{
		unset($data['id']);
		unset($data['currency']);
		unset($data['currency_name']);
	}

	protected function getIfNecessary()
	{
		if (null === $this->data)
		{
			$this->get();
		}
	}

	/** {@inheritdoc} */
	public function __get($name)
	{
		if (null === $this->data)
		{
			$this->getIfNecessary();
		}

		return parent::__get($name);
	}

}
