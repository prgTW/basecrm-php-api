<?php

namespace prgTW\BaseCRM\Service;

use prgTW\BaseCRM\Resource\InstanceResource;

/**
 * @method Notes getNotes()
 * @method Reminders getReminders()
 */
class Contact extends InstanceResource
{
	/** {@inheritdoc} */
	protected function getEndpoint()
	{
		return self::ENDPOINT_SALES;
	}

	/** {@inheritdoc} */
	protected function init()
	{
		$this->setSubResources([
//			Contacts::class,
//			Deals::class,
			Notes::class,
			Reminders::class,
		]);
	}

	/** {@inheritdoc} */
	protected function preDehydrate()
	{
		return array_intersect_key($this->data, array_flip([
			'name',
			'last_name',
			'first_name',
			'contact_id',
			'email',
			'phone',
			'mobile',
			'twitter',
			'skype',
			'facebook',
			'linkedin',
			'address',
			'city',
			'country',
			'title',
			'description',
			'industry',
			'website',
			'fax',
			'tag_list',
			'private',
		]));
	}
}
