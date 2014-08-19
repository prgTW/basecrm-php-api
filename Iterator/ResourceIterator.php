<?php

namespace prgTW\BaseCRM\Iterator;

class ResourceIterator implements \Iterator
{
	/** @var Resource[] */
	protected $resources;

	/**
	 * @param array $resources
	 */
	public function __construct(array $resources)
	{
		$this->resources = $resources;
	}

	/**
	 * @return Resource|null
	 */
	public function current()
	{
		return current($this->resources);
	}

	/**
	 * @return Resource|null
	 */
	public function next()
	{
		return next($this->resources);
	}

	/** {@inheritdoc} */
	public function key()
	{
		return key($this->resources);
	}

	/** {@inheritdoc} */
	public function valid()
	{
		return null !== key($this->resources);
	}

	/** {@inheritdoc} */
	public function rewind()
	{
		reset($this->resources);
	}
}
