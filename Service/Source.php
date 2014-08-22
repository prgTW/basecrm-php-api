<?php

namespace prgTW\BaseCRM\Service;

use prgTW\BaseCRM\Resource\InstanceResource;

class Source extends InstanceResource
{
	/** {@inheritdoc} */
	protected function getEndpoint()
	{
		return self::ENDPOINT_SALES;
	}

	/** {@inheritdoc} */
	protected function preDehydrate()
	{
		return array_intersect_key($this->data, array_flip([
			'name',
		]));
	}
}
