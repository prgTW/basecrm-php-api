<?php

namespace prgTW\BaseCRM\Service;

trait TagTrait
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
	 * @return array
	 */
	public function getTagList()
	{
		return explode(',', $this->tagList);
	}

	/**
	 * @param array $tagList
	 *
	 * @return $this
	 */
	public function setTagList(array $tagList)
	{
		$this->tagList = implode(',', $tagList);

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

	public function postDehydrate(array &$data)
	{
		unset($data['id']);
		unset($data['permissions_holder_id']);
	}
}
