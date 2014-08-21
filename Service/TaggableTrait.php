<?php

namespace prgTW\BaseCRM\Service;

use prgTW\BaseCRM\Service\Detached\Tag;
use prgTW\BaseCRM\Utils\TaggingAppId;

trait TaggableTrait
{
	/**
	 * @param array $tags
	 *
	 * @return bool
	 */
	public function saveTags(array $tags)
	{
		$classNameOnly = $this->getClassNameOnly();

		$tagging      = TaggingAppId::fromName($classNameOnly);
		$appId        = $tagging->getValue();
		$taggableType = $tagging->getName();
		$taggableId   = $this->getId();

		$tagging = (new Tag)
			->setAppId($appId)
			->setTaggableType($taggableType)
			->setTaggableId($taggableId)
			->setTagList($tags);

		/** @var Tags $taggings */
		$taggings = $this->getTags();
		$result   = $taggings->create($tagging, false);

		return $result;
	}
}
