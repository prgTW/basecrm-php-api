<?php

namespace prgTW\BaseCRM\Service;

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
}