<?php

namespace prgTW\BaseCRM\Service\Behavior;

use prgTW\BaseCRM\Service\Detached\Tag;
use prgTW\BaseCRM\Service\Tags;
use prgTW\BaseCRM\Utils\TagAppId;

/**
 * @method string getClassNameOnly()
 * @method Tags   getTags()
 */
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

		$tag          = TagAppId::fromName($classNameOnly);
		$appId        = $tag->getValue();
		$taggableType = $tag->getName();
		$taggableId   = $this->id;

		$tag               = new Tag;
		$tag->appId        = $appId;
		$tag->taggableType = $taggableType;
		$tag->taggableId   = $taggableId;
		$tag->tagList      = implode(',', $tags);

		/** @var Tags $tags */
		$tags   = $this->getTags();
		$result = $tags->create($tag, false);

		return $result;
	}
}
