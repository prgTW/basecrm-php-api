<?php

namespace prgTW\BaseCRM\Service;

use prgTW\BaseCRM\Resource\InstanceResource;

class Source extends InstanceResource
{
	/** @var int */
	protected $id;

	/** @var int */
	protected $userId;

	/** @var int */
	protected $permissionHolderId;

	/** @var string */
	protected $name;

	/** @var int */
	protected $dealsCount;

	/** @var \DateTime */
	protected $createdAt;

	/** @var \DateTime */
	protected $updatedAt;

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
		return $this->id;
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
		return $this->name;
	}

	/**
	 * @return int
	 */
	public function getPermissionHolderId()
	{
		return $this->permissionHolderId;
	}

	/**
	 * @return \DateTime
	 */
	public function getUpdatedAt()
	{
		return new \DateTime($this->updatedAt);
	}

	/**
	 * @return int
	 */
	public function getUserId()
	{
		return $this->userId;
	}

	/**
	 * @return int
	 */
	public function getDealsCount()
	{
		return $this->dealsCount;
	}

	/**
	 * @return \DateTime
	 */
	public function getCreatedAt()
	{
		return new \DateTime($this->createdAt);
	}

	/** {@inheritdoc} */
	protected function preDehydrate()
	{
		return [
			'name' => $this->getName(),
		];
	}

}
