<?php

namespace prgTW\BaseCRM\Resource;

class AutoPagingIterator implements \Iterator, \Countable
{
	/** @var callable */
	protected $generator;

	/** @var array */
	protected $args;

	/** @var int */
	protected $page;

	/** @var string */
	protected $sortBy;

	/** @var ResourceCollection */
	protected $resourceCollection;

	/**
	 * @param callable $generator
	 * @param int      $page
	 * @param string   $sortBy
	 *
	 * @throws \InvalidArgumentException when generator is not callable
	 */
	public function __construct($generator, $page, $sortBy)
	{
		if (false === is_callable($generator))
		{
			throw new \InvalidArgumentException('Generator is not callable');
		}

		$this->generator = $generator;
		$this->args      = array(
			'page'    => $page,
			'sort_by' => $sortBy,
		);
		$this->reset();
	}

	protected function reset()
	{
		$this->page               = $this->args['page'];
		$this->sortBy             = $this->args['sort_by'];
		$this->resourceCollection = null;
	}

	/** {@inheritdoc} */
	public function current()
	{
		return $this->resourceCollection->current();
	}

	/** {@inheritdoc} */
	public function key()
	{
		return $this->resourceCollection->key();
	}

	/** {@inheritdoc} */
	public function next()
	{
		$this->loadIfNecessary();

		return $this->resourceCollection->next();
	}

	/** {@inheritdoc} */
	public function valid()
	{
		$this->loadIfNecessary();

		return null !== $this->key();
	}

	/** {@inheritdoc} */
	public function rewind()
	{
		$this->reset();
	}

	/** {@inheritdoc} */
	public function count()
	{
		throw new \BadMethodCallException('Not allowed');
	}

	protected function loadIfNecessary()
	{
		if (null === $this->resourceCollection || null === $this->key())
		{
			/** @var Page $page */
			$page = call_user_func_array($this->generator, array(
				$this->page,
				$this->sortBy,
			));

			$this->resourceCollection = $page->getResourceCollection();
			++$this->page;
		}
	}
}