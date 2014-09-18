<?php

namespace prgTW\BaseCRM\Service;

use prgTW\BaseCRM\Resource\InstanceResource;
use prgTW\BaseCRM\Service\Behavior\CustomFieldsTrait;

class Source extends InstanceResource
{
	use CustomFieldsTrait;

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
