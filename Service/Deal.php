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
	public function save(array $fieldNames)
	{
		$fieldNames = array_intersect_key($fieldNames, [
			'name',
			'entity_id',
			'scope',
			'hot',
			'deal_tags',
			'contact_ids',
			'source_id',
			'stage',
		]);

		return parent::save($fieldNames);
	}
}
