<?php

namespace prgTW\BaseCRM\Resource;

use prgTW\BaseCRM\Service\Behavior\CustomFieldsTrait;

class DetachedResource extends BaseResource
{
	/** {@inheritdoc} */
	protected function postDehydrate(array &$data)
	{
		parent::postDehydrate($data);

		if (false === in_array(CustomFieldsTrait::class, class_uses($this)))
		{
			return;
		}

		/** @var CustomFieldsTrait $this */
		$customFieldsKey        = $this->getCustomFieldsKey();
		$customFieldsCollection = $this->getCustomFields();
		if (null !== $customFieldsCollection)
		{
			$data[$customFieldsKey] = $customFieldsCollection->toArray();
		}
	}
}
