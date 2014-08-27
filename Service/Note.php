<?php

namespace prgTW\BaseCRM\Service;

use prgTW\BaseCRM\Resource\InstanceResource;

class Note extends InstanceResource
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
			'content',
		]);

		return parent::save($fieldNames);
	}
}
