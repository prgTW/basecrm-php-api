<?php

namespace prgTW\BaseCRM\Service;

use prgTW\BaseCRM\Resource\Partial\CreateResource;
use prgTW\BaseCRM\Resource\Resource;

class Taggings extends Resource
{
	use CreateResource;

	/** {@inheritdoc} */
	protected function getEndpoint()
	{
		return self::ENDPOINT_TAGS;
	}
}
