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
	 * @param int $id
	 *
	 * @return $this
	 */
	public function setId($id)
	{
		$this->id = $id;

		return $this;
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
	 * @param int $permissionHolderId
	 *
	 * @return $this
	 */
	public function setPermissionHolderId($permissionHolderId)
	{
		$this->permissionHolderId = $permissionHolderId;

		return $this;
	}

	/**
	 * @return int
	 */
	public function getPermissionHolderId()
	{
		return $this->permissionHolderId;
	}

	/**
	 * @param \DateTime $updatedAt
	 *
	 * @return $this
	 */
	public function setUpdatedAt(\DateTime $updatedAt)
	{
		$this->updatedAt = $updatedAt->format(\DateTime::W3C);

		return $this;
	}

	/**
	 * @return \DateTime
	 */
	public function getUpdatedAt()
	{
		return new \DateTime($this->updatedAt);
	}

	/**
	 * @param int $userId
	 *
	 * @return $this
	 */
	public function setUserId($userId)
	{
		$this->userId = $userId;

		return $this;
	}

	/**
	 * @return int
	 */
	public function getUserId()
	{
		return $this->userId;
	}

	/**
	 * @param int $dealsCount
	 *
	 * @return $this
	 */
	public function setDealsCount($dealsCount)
	{
		$this->dealsCount = $dealsCount;

		return $this;
	}

	/**
	 * @return int
	 */
	public function getDealsCount()
	{
		return $this->dealsCount;
	}

	/**
	 * @param \DateTime $createdAt
	 *
	 * @return $this
	 */
	public function setCreatedAt(\DateTime $createdAt)
	{
		$this->createdAt = $createdAt->format(\DateTime::W3C);

		return $this;
	}

	/**
	 * @return \DateTime
	 */
	public function getCreatedAt()
	{
		return new \DateTime($this->createdAt);
	}
}
