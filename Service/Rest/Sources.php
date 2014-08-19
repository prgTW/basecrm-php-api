<?php

namespace prgTW\BaseCRM\Service\Rest;

use prgTW\BaseCRM\Resource\ListResource;

class Sources extends ListResource
{
	/** {@inheritdoc} */
	protected function getEndpoint()
	{
		return self::ENDPOINT_SALES;
	}
}
