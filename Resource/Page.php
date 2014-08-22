<?php

namespace prgTW\BaseCRM\Resource;

class Page implements \IteratorAggregate
{
	/** @var array */
	protected $page;

	/** @var ResourceCollection */
	protected $resourceCollection;

	public function __construct(array $page, $resourceName)
	{
		$this->page               = $page;
		$items                    = array_key_exists('items', $page) ? $page['items'] : $page;
		$this->resourceCollection = new ResourceCollection($items);
	}

	/**
	 * @return ResourceCollection
	 */
	public function getResourceCollection()
	{
		return $this->resourceCollection;
	}

	/** {@inheritdoc} */
	public function getIterator()
	{
		return $this->getResourceCollection();
	}
}
