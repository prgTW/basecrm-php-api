<?php

namespace prgTW\BaseCRM\Service\Detached;

use prgTW\BaseCRM\Resource\DetachedResource;

class Lead extends DetachedResource
{
	/** {@inheritdoc} */
	protected function preDehydrate()
	{
		return array_intersect_key($this->data, array_flip([
			'first_name',
			'last_name',
			'company_name',
			'email',
			'phone',
			'mobile',
			'address',
			'city',
			'country',
			'title',
			'description',
		]));
	}
}
