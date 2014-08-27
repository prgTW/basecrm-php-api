<?php

namespace prgTW\BaseCRM\Service;

use prgTW\BaseCRM\Resource\DetachedResource;
use prgTW\BaseCRM\Resource\ListResource;

class Reminders extends ListResource
{
	/** {@inheritdoc} */
	protected function getEndpoint()
	{
		return self::ENDPOINT_SALES;
	}

	/** {@inheritdoc} */
	public function create(DetachedResource $resource, array $fieldNames = [], $useKey = true)
	{
		$fieldNames = array_intersect_key($fieldNames, [
			'content',
			'done',
			'remind',
			'date',
			'hour',
		]);

		return parent::create($resource, $fieldNames, $useKey);
	}

}
