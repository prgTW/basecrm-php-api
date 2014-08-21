<?php

namespace prgTW\BaseCRM\Service;

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
}
