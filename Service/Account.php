<?php

namespace prgTW\BaseCRM\Service;

use prgTW\BaseCRM\Resource\InstanceResource;
use prgTW\BaseCRM\Service\Enum\Currency;

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
		return new Currency($this->data['currency_id']);
	}

	/**
	 * @return string
	 */
	protected function getName()
	{
		return $this->data['name'];
	}

	/**
	 * @return string
	 */
	protected function getTimezone()
	{
		return $this->data['timezone'];
	}

	/** {@inheritdoc} */
	public function save(array $fieldNames = [])
	{
		$fieldNames = array_intersect($fieldNames, [
			'name',
			'timezone',
			'currency_id',
		]);

		return parent::save($fieldNames);
	}

	/** {@inheritdoc} */
	protected function postHydrate(array $data)
	{
		$this->currency = Currency::fromName($data['currency_name']);
	}
}
