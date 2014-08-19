<?php

namespace prgTW\BaseCRM\Service;

use prgTW\BaseCRM\Resource\ListResource;

class Sources extends ListResource
{
	/** {@inheritdoc} */
	protected function getEndpoint()
	{
		return self::ENDPOINT_SALES;
	}
}
