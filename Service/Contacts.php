<?php

namespace prgTW\BaseCRM\Service;

use prgTW\BaseCRM\Resource\DetachedResource;
use prgTW\BaseCRM\Resource\Page;
use prgTW\BaseCRM\Resource\PaginatedListResource;

class Contacts extends PaginatedListResource
{
	/** {@inheritdoc} */
	protected function getEndpoint()
	{
		return self::ENDPOINT_SALES;
	}

	/**
	 * @param int $page
	 *
	 * @return Page
	 */
	public function all($page = 1)
	{
		return parent::getPage($page);
	}

	/** {@inheritdoc} */
	public function create(DetachedResource $resource, array $fieldNames, $useKey = true)
	{
		$fieldNames = array_intersect_key($fieldNames, [
			'name',
			'last_name',
			'first_name',
			'is_organisation',
			'contact_id',
			'email',
			'phone',
			'mobile',
			'twitter',
			'skype',
			'facebook',
			'linkedin',
			'address',
			'city',
			'country',
			'title',
			'description',
			'industry',
			'website',
			'fax',
			'tag_list',
			'private',
		]);

		return parent::create($resource, $fieldNames, $useKey);
	}
}
