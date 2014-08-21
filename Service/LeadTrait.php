<?php

namespace prgTW\BaseCRM\Service;

trait LeadTrait
{
	/** @var int */
	protected $id;

	/**
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}
}
