<?php

namespace prgTW\BaseCRM\Service;

use prgTW\BaseCRM\Resource\PaginatedListResource;
use prgTW\BaseCRM\Resource\ResourceCollection;
use prgTW\BaseCRM\Service\Enum\LeadsSortBy;

class Leads extends PaginatedListResource
{
	/** {@inheritdoc} */
	protected function getEndpoint()
	{
		return self::ENDPOINT_LEADS;
	}

	/**
	 * @param int         $page
	 * @param LeadsSortBy $sortBy
	 *
	 * @return ResourceCollection
	 */
	public function all($page = 1, LeadsSortBy $sortBy = null)
	{
		$query = [];
		if (null !== $sortBy)
		{
			$query['sort_by'] = $sortBy->getValue();
		}

		return parent::getPage($page, $query);
	}
}
