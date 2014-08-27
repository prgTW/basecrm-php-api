<?php

namespace prgTW\BaseCRM\Service;

use prgTW\BaseCRM\Resource\DetachedResource;
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

	/** {@inheritdoc} */
	public function create(DetachedResource $resource, array $fieldNames = [], $useKey = true)
	{
		$fieldNames = array_intersect_key($fieldNames, [
			'last_name',
			'company_name',
			'first_name',
			'email',
			'phone',
			'mobile',
			'twitter',
			'skype',
			'facebook',
			'linkedin',
			'street',
			'zip',
			'region',
			'city',
			'city',
			'country',
			'title',
			'description',
		]);

		return parent::create($resource, $fieldNames, $useKey);
	}
}
