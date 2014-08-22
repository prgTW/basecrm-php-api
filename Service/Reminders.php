<?php

namespace prgTW\BaseCRM\Service;

use prgTW\BaseCRM\Resource\ListResource;

class Reminders extends ListResource
{
	/** {@inheritdoc} */
	protected function getEndpoint()
	{
		return self::ENDPOINT_SALES;
	}
}
