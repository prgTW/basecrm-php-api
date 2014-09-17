<?php

namespace prgTW\BaseCRM\Resource;

class CustomField
{
	/** @var string */
	protected $name;

	/** @var array */
	protected $data;

	/**
	 * @param string $name
	 * @param mixed  $dataOrValue
	 */
	public function __construct($name, $dataOrValue)
	{
		$this->name = $name;
		if (false == is_array($dataOrValue))
		{
			$dataOrValue = [
				'id'    => null,
				'value' => $dataOrValue
			];
		}
		$this->data = $dataOrValue;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @return null|int
	 */
	public function getId()
	{
		return $this->data['id'];
	}

	/**
	 * @return mixed
	 */
	public function getValue()
	{
		return false === array_key_exists('value', $this->data) ? null : $this->data['value'];
	}

	/**
	 * @return array
	 */
	public function getData()
	{
		return $this->data;
	}
}
