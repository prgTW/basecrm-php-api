<?php

namespace prgTW\BaseCRM\Service\Behavior;

use prgTW\BaseCRM\Resource\ResourceCollection;
use prgTW\BaseCRM\Service\Detached\Tag;
use prgTW\BaseCRM\Service\Enum\TagAppId;
use prgTW\BaseCRM\Service\Tags;

/**
 * @method string getClassNameOnly()
 * @method Tags   getTags()
 */
trait TaggableTrait
{
	/**
	 * @param string[] $tags
	 *
	 * @return ResourceCollection
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
		$result = $tags->create($tag, [
			'app_id',
			'taggable_type',
			'taggable_id',
			'tag_list',
		], false);

		return $result;
	}
}
