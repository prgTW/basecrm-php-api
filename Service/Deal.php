<?php

namespace prgTW\BaseCRM\Service;

use prgTW\BaseCRM\Resource\InstanceResource;
use prgTW\BaseCRM\Service\Behavior\NoteableTrait;
use prgTW\BaseCRM\Service\Behavior\RemindableTrait;

/**
 * @method Contacts getContacts()
 */
class Deal extends InstanceResource
{
	use NoteableTrait;
	use RemindableTrait;

	/** {@inheritdoc} */
	protected function getEndpoint()
	{
		return self::ENDPOINT_SALES;
	}

	/** {@inheritdoc} */
	protected function init()
	{
		$this->setSubResources([
			Contacts::class,
		]);
	}

	/** {@inheritdoc} */
	protected function preDehydrate()
	{
		return array_intersect_key($this->data, array_flip([
			'name',
			'entity_id',
			'scope',
			'hot',
			'deal_tags',
			'contact_ids',
			'source_id',
			'stage',
		]));
	}
}
