<?php

namespace prgTW\BaseCRM\Service;

use prgTW\BaseCRM\Iterator\ResourceIterator;

class ResourceCollection implements \ArrayAccess, \IteratorAggregate
{
	/** @var Resource[] */
	protected $items;

	/**
	 * @param array $resources
	 */
	public function __construct(array $resources)
	{
		$this->items = $resources;
	}

	/**
	 * @return Resource[]
	 */
	public function getItems()
	{
		return $this->items;
	}

	/** {@inheritdoc} */
	public function offsetExists($offset)
	{
		return array_key_exists($offset, $this->items);
	}

	/** {@inheritdoc} */
	public function offsetGet($offset)
	{
		return $this->items[$offset];
	}

	/** {@inheritdoc} */
	public function offsetSet($offset, $value)
	{
		$this->items[$offset] = $value;
	}

	/** {@inheritdoc} */
	public function offsetUnset($offset)
	{
		unset($this->items[$offset]);
	}

	/** {@inheritdoc} */
	public function getIterator()
	{
		$items = $this->getItems();

		return new ResourceIterator($items);
	}
}
