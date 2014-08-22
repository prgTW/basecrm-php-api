<?php

namespace prgTW\BaseCRM\Service;

use prgTW\BaseCRM\Resource\PaginatedListResource;

class Leads extends PaginatedListResource
{
	/** {@inheritdoc} */
	protected function getEndpoint()
	{
		return self::ENDPOINT_LEADS;
	}

	/** {@inheritdoc} */
	public function all($page = 1, $sortBy = null)
	{
		$query = [];
		if (null !== $sortBy)
		{
			$query['sort_by'] = $sortBy;
		}

		return parent::getPage($page, $query);
	}
}
