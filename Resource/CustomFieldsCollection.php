<?php

namespace prgTW\BaseCRM\Resource;

class CustomFieldsCollection implements \ArrayAccess, \Iterator, \Countable
{
	/** @var array|CustomField[] */
	protected $customFields;

	/**
	 * @param array|CustomField[] $customFields
	 */
	public function __construct(array $customFields)
	{
		$this->customFields = $customFields;
		$this->rewind();
	}

	/**
	 * @return array
	 */
	public function toArray()
	{
		$array = [];
		foreach ($this as $customField)
		{
			$array[$customField->getName()] = $customField->getValue();
		}

		return $array;
	}

	/**
	 * @return array|CustomField[]
	 */
	public function getItems()
	{
		return $this->customFields;
	}

	/** {@inheritdoc} */
	public function offsetExists($offset)
	{
		return array_key_exists($offset, $this->customFields);
	}

	/** {@inheritdoc} */
	public function offsetGet($offset)
	{
		return $this->customFields[$offset];
	}

	/** {@inheritdoc} */
	public function offsetSet($offset, $value)
	{
		$this->customFields[$offset] = $value;
	}

	/** {@inheritdoc} */
	public function offsetUnset($offset)
	{
		unset($this->customFields[$offset]);
	}

	/**
	 * @return CustomField|null
	 */
	public function current()
	{
		return current($this->customFields);
	}

	/**
	 * @return CustomField|null
	 */
	public function next()
	{
		return next($this->customFields);
	}

	/** {@inheritdoc} */
	public function key()
	{
		return key($this->customFields);
	}

	/** {@inheritdoc} */
	public function valid()
	{
		return null !== $this->key();
	}

	/** {@inheritdoc} */
	public function rewind()
	{
		reset($this->customFields);
	}

	/** {@inheritdoc} */
	public function count()
	{
		return count($this->customFields);
	}
}
