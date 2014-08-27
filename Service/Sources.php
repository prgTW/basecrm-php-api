<?php

namespace prgTW\BaseCRM\Service;

use prgTW\BaseCRM\Resource\DetachedResource;
use prgTW\BaseCRM\Resource\ListResource;
use prgTW\BaseCRM\Resource\ResourceCollection;

class Sources extends ListResource
{
	/** {@inheritdoc} */
	protected function getEndpoint()
	{
		return self::ENDPOINT_SALES;
	}

	/**
	 * @param bool $other Retrieves sources which were added by other users in the account
	 *
	 * @return ResourceCollection
	 */
	public function all($other = false)
	{
		$query = [];
		if (true === $other)
		{
			$query['other'] = 1;
		}

		return parent::all($query);
	}

	/** {@inheritdoc} */
	public function create(DetachedResource $resource, array $fieldNames = [], $useKey = true)
	{
		$fieldNames = array_intersect($fieldNames, [
			'name',
		]);

		return parent::create($resource, $fieldNames, $useKey);
	}
}
