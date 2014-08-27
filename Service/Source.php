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
	public function save(array $fieldNames = [])
	{
		$fieldNames = array_intersect($fieldNames, [
			'name',
		]);

		return parent::save($fieldNames);
	}
}
