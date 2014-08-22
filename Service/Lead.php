<?php

namespace prgTW\BaseCRM\Service;

use prgTW\BaseCRM\Resource\InstanceResource;

/**
 * @method Tags getTaggings()
 */
class Lead extends InstanceResource
{
	use LeadTrait;
	use TaggableTrait;

	/** {@inheritdoc} */
	protected function getEndpoint()
	{
		return self::ENDPOINT_LEADS;
	}

	/** {@inheritdoc} */
	protected function init()
	{
		$this->setSubResources([
			Tags::class => 'taggings',
		]);
	}

	/** {@inheritdoc} */
	protected function preDehydrate()
	{
		return [
			'first_name'   => $this->firstName,
			'last_name'    => $this->lastName,
			'company_name' => $this->companyName,
		];
	}
}
