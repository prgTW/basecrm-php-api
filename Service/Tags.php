<?php

namespace prgTW\BaseCRM\Service;

use prgTW\BaseCRM\Resource\DetachedResource;
use prgTW\BaseCRM\Resource\ListResource;
use prgTW\BaseCRM\Resource\Partial\CreateResource;

class Tags extends ListResource
{
	use CreateResource;

	/** {@inheritdoc} */
	protected function getEndpoint()
	{
		return self::ENDPOINT_TAGS;
	}

	/** {@inheritdoc} */
	public function create(DetachedResource $resource, array $fieldNames = [], $useKey = true)
	{
		$fieldNames = array_intersect_key($fieldNames, [
			'app_id',
			'taggable_type',
			'taggable_id',
			'tag_list',
		]);

		return parent::create($resource, $fieldNames, $useKey);
	}
}
