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
	public function save(array $fieldNames = [])
	{
		$fieldNames = array_intersect($fieldNames, [
			'first_name',
			'last_name',
			'company_name',
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
		]);

		return parent::save($fieldNames);
	}
}
