<?php

namespace prgTW\BaseCRM\Resource;

class ResourceCollection implements \ArrayAccess, \Iterator, \Countable
{
	/** @var Resource[] */
	protected $items;

	/**
	 * @param array $resources
	 */
	public function __construct(array $resources)
	{
		$this->items = $resources;
		$this->rewind();
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

	/**
	 * @return Resource|null
	 */
	public function current()
	{
		return current($this->items);
	}

	/**
	 * @return Resource|null
	 */
	public function next()
	{
		return next($this->items);
	}

	/** {@inheritdoc} */
	public function key()
	{
		return key($this->items);
	}

	/** {@inheritdoc} */
	public function valid()
	{
		return null !== $this->key();
	}

	/** {@inheritdoc} */
	public function rewind()
	{
		reset($this->items);
	}

	/** @{inheritdoc} */
	public function count()
	{
		return count($this->items);
	}
}
