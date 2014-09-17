<?php

namespace prgTW\BaseCRM\Service\Behavior;

use Instantiator\Exception\InvalidArgumentException;
use prgTW\BaseCRM\Resource\CustomField;
use prgTW\BaseCRM\Resource\CustomFieldsCollection;

/**
 * @property-read array $data
 */
trait CustomFieldsTrait
{
	/**
	 * @param string $name
	 *
	 * @return bool
	 *
	 * @throws \InvalidArgumentException when name is not string
	 */
	public function hasCustomField($name)
	{
		if (false === is_string($name))
		{
			throw new InvalidArgumentException('fieldName is not string');
		}

		$key = $this->getCustomFieldsKey();
		if (false === array_key_exists($key, $this->data))
		{
			return false;
		}

		return array_key_exists($name, $this->data[$key]);
	}

	/**
	 * @param string $name
	 *
	 * @return null|CustomField
	 * @throws \InvalidArgumentException when name is not string
	 */
	public function getCustomField($name)
	{
		if (false === is_string($name))
		{
			throw new InvalidArgumentException('fieldName is not string');
		}

		if (false === $this->hasCustomField($name))
		{
			return null;
		}

		$key = $this->getCustomFieldsKey();

		return new CustomField($name, $this->data[$key][$name]);
	}

	/**
	 * @param string $name
	 * @param mixed  $value
	 */
	public function setCustomField($name, $value)
	{
		$customField             = new CustomField($name, $value);
		$key                     = $this->getCustomFieldsKey();
		$this->data[$key][$name] = $customField->getData();
	}

	/**
	 * @return null|CustomFieldsCollection
	 */
	public function getCustomFields()
	{
		$key = $this->getCustomFieldsKey();

		if (false === array_key_exists($key, $this->data))
		{
			return new CustomFieldsCollection([]);
		}

		$customFields = [];
		foreach (array_keys($this->data[$key]) as $fieldName)
		{
			$customFields[$fieldName] = $this->getCustomField($fieldName);
		}

		return new CustomFieldsCollection($customFields);
	}

	protected function getCustomFieldsKey()
	{
		return 'custom_fields';
	}
}
