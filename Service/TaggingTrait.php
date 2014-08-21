<?php

namespace prgTW\BaseCRM\Service;

trait TaggingTrait
{
	/** @var int */
	protected $id;

	/** @var int */
	protected $appId;

	/** @var string */
	protected $taggableType;

	/** @var int */
	protected $taggableId;

	/** @var string */
	protected $tagList;

	/** @var int */
	protected $permissionsHolderId;

	/**
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @return int
	 */
	public function getAppId()
	{
		return $this->appId;
	}

	/**
	 * @param int $appId
	 *
	 * @return $this
	 */
	public function setAppId($appId)
	{
		$this->appId = $appId;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getTagList()
	{
		return $this->tagList;
	}

	/**
	 * @param string $tagList
	 *
	 * @return $this
	 */
	public function setTagList($tagList)
	{
		$this->tagList = $tagList;

		return $this;
	}

	/**
	 * @return int
	 */
	public function getTaggableId()
	{
		return $this->taggableId;
	}

	/**
	 * @param int $taggableId
	 *
	 * @return $this
	 */
	public function setTaggableId($taggableId)
	{
		$this->taggableId = $taggableId;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getTaggableType()
	{
		return $this->taggableType;
	}

	/**
	 * @param string $taggableType
	 *
	 * @return $this
	 */
	public function setTaggableType($taggableType)
	{
		$this->taggableType = $taggableType;

		return $this;
	}

	/**
	 * @return int
	 */
	public function getPermissionsHolderId()
	{
		return $this->permissionsHolderId;
	}
}
