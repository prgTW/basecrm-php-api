<?php

namespace prgTW\BaseCRM\Service;

use prgTW\BaseCRM\Resource\InstanceResource;
use prgTW\BaseCRM\Service\Behavior\TaggableTrait;

/**
 * @method Tags getTaggings()
 */
class Lead extends InstanceResource
{
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
		return array_intersect_key($this->data, array_flip([
			'first_name',
			'last_name',
			'company_name',
		]));
	}
}
