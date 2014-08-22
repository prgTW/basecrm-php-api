<?php

namespace prgTW\BaseCRM\Service;

/**
 * @property-read int    id
 * @property int         appId
 * @property string      taggableType
 * @property int         taggableId
 * @property string      tagList
 * @property-read int    permissionsHolderId
 */
trait TagTrait
{
	/** {@inheritdoc} */
	protected function preDehydrate()
	{
		return [
			'app_id'        => $this->appId,
			'taggable_id'   => $this->taggableId,
			'taggable_type' => $this->taggableType,
			'tag_list'      => $this->tagList,
		];
	}
}
