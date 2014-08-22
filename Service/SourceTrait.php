<?php

namespace prgTW\BaseCRM\Service;

/**
 * @property-read int         id
 * @property-read int         userId
 * @property-read int         permissionHolderId
 * @property      string      name
 * @property-read int         dealsCount
 * @property-read string      createdAt
 * @property-read string      updatedAt
 */
trait SourceTrait
{
	/** {@inheritdoc} */
	protected function preDehydrate()
	{
		return [
			'name' => $this->name,
		];
	}
}
