<?php

namespace prgTW\BaseCRM\Service\Detached;

use prgTW\BaseCRM\Resource\DetachedResource;
use prgTW\BaseCRM\Service\LeadTrait;

/**
 * @property string email
 * @property string phone
 * @property string mobile
 * @property string address
 * @property string city
 * @property string street
 * @property string zip
 * @property string region
 * @property string country
 * @property string title
 * @property string description
 * @property string twitter
 * @property string skype
 * @property string facebook
 * @property string linkedin
 */
class Lead extends DetachedResource
{
	use LeadTrait;

	/** {@inheritdoc} */
	protected function preDehydrate()
	{
		return [
			'first_name'   => $this->firstName,
			'last_name'    => $this->lastName,
			'company_name' => $this->companyName,
			'email'        => $this->email,
			'phone'        => $this->phone,
			'mobile'       => $this->mobile,
			'address'      => $this->address,
			'city'         => $this->city,
			'country'      => $this->country,
			'title'        => $this->title,
			'description'  => $this->description,
		];
	}
}
