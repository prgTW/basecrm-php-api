<?php

namespace prgTW\BaseCRM\Service;

use prgTW\BaseCRM\Exception\ResourceException;
use prgTW\BaseCRM\Resource\InstanceResource;

class Tag extends InstanceResource
{
	/** {@inheritdoc} */
	protected function getEndpoint()
	{
		return self::ENDPOINT_TAGS;
	}

	/** {@inheritdoc} */
	public function get()
	{
		throw new ResourceException('This resource doesn\'t support this operation');
	}

	/** {@inheritdoc} */
	public function save(array $fieldNames = [])
	{
		throw new ResourceException('This resource doesn\'t support this operation');
	}
}
