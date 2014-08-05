<?php

namespace prgTW\BaseCRM\Service\Rest;

use prgTW\BaseCRM\Service\InstanceResource;
use prgTW\BaseCRM\Utils\Currency;

class Account extends InstanceResource
{
	/** @var int */
	protected $id;

	/** @var string */
	protected $name;

	/** @var string */
	protected $timezone;

	/** @var Currency */
	protected $currency;

	/** @var int */
	protected $currencyId;

	/** @var string */
	protected $currencyName;

	/** {@inheritdoc} */
	protected function getEndpoint()
	{
		return self::ENDPOINT_SALES;
	}

	/**
	 * @return int
	 */
	public function getId()
	{
		if (null === $this->id)
		{
			$this->get();
		}

		return $this->id;
	}

	/**
	 * @param Currency $currency
	 *
	 * @return $this
	 */
	public function setCurrency(Currency $currency)
	{
		$this->currency     = $currency;
		$this->currencyId   = $currency->getValue();
		$this->currencyName = Currency::getNames()[$currency->getValue()];

		return $this;
	}

	/**
	 * @return Currency
	 */
	public function getCurrency()
	{
		if (null === $this->id)
		{
			$this->get();
		}

		return $this->currency;
	}

	/**
	 * @param string $name
	 *
	 * @return $this
	 */
	public function setName($name)
	{
		$this->name = $name;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		if (null === $this->id)
		{
			$this->get();
		}

		return $this->name;
	}

	/**
	 * @param string $timezone
	 *
	 * @return $this
	 */
	public function setTimezone($timezone)
	{
		$this->timezone = $timezone;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getTimezone()
	{
		if (null === $this->id)
		{
			$this->get();
		}

		return $this->timezone;
	}

	/** {@inheritdoc} */
	protected function postHydrate(array $data)
	{
		$this->currency = Currency::fromName($data['account']['currency_name']);
	}

	/** {@inheritdoc} */
	protected function postDehydrate(array &$data)
	{
		unset($data['account']['currency']);
		unset($data['account']['currency_name']);
	}
}
