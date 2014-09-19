<?php

namespace prgTW\BaseCRM\Error;

class Error
{
	/** @var string */
	protected $code;

	/** @var string */
	protected $field;

	/** @var string */
	protected $description;

	/**
	 * @param string $code
	 * @param string $field
	 * @param string $description
	 */
	public function __construct($code, $field, $description)
	{
		$this->code        = $code;
		$this->field       = $field;
		$this->description = $description;
	}

	/**
	 * @return string
	 */
	public function getCode()
	{
		return $this->code;
	}

	/**
	 * @return string
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * @return string
	 */
	public function getField()
	{
		return $this->field;
	}
}
