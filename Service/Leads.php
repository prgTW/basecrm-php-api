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
}
