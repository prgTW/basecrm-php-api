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

	/** @var array */
	protected $query;

	/** @var string */
	protected $sortBy;

	/** @var ResourceCollection */
	protected $resourceCollection;

	/**
	 * @param callable $generator
	 * @param int      $page
	 * @param array    $query
	 *
	 * @throws \InvalidArgumentException when generator is not callable
	 */
	public function __construct($generator, $page, array $query = [])
	{
		if (false === is_callable($generator))
		{
			//@codeCoverageIgnoreStart
			throw new \InvalidArgumentException('Generator is not callable');
			//@codeCoverageIgnoreEnd
		}

		$this->generator = $generator;
		$this->args      = array(
			'page'  => $page,
			'query' => $query,
		);
		$this->reset();
	}

	protected function reset()
	{
		$this->page               = $this->args['page'];
		$this->query              = $this->args['query'];
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
		//@codeCoverageIgnoreStart
		throw new \BadMethodCallException('Not allowed');
		//@codeCoverageIgnoreEnd
	}

	protected function loadIfNecessary()
	{
		if (null === $this->resourceCollection || null === $this->key())
		{
			/** @var Page $page */
			$page = call_user_func_array($this->generator, array(
				$this->page,
				$this->query,
			));

			$this->resourceCollection = $page->getResourceCollection();
			++$this->page;
		}
	}
}
