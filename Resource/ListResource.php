<?php

namespace prgTW\BaseCRM\Resource;

use Doctrine\Common\Inflector\Inflector;
use prgTW\BaseCRM\Resource\Partial\CreateResource;
use prgTW\BaseCRM\Transport\Transport;

abstract class ListResource extends Resource implements \IteratorAggregate, \Countable
{
	use CreateResource;

	/** @var ResourceCollection */
	protected $collection;

	/** @var array */
	private $query;

	/** {@inheritdoc} */
	public function __construct(Transport $transport, $uri)
	{
		parent::__construct($transport, $uri);
		$this->reset();
	}

	protected function reset()
	{
		$this->collection = null;
		$this->query      = null;
	}

	/**
	 * @param string $id
	 *
	 * @return InstanceResource
	 */
	public function get($id)
	{
		$instanceClassName = Inflector::singularize(static::class);
		$uri               = sprintf('%s/%s', $this->uri, $id);
		/** @var InstanceResource $resource */
		$resource = new $instanceClassName($this->transport, $uri);

		return $resource;
	}

	/**
	 * @param string $id
	 *
	 * @return bool
	 */
	public function delete($id)
	{
		$uri    = $this->getFullUri(sprintf('/%d', $id));
		$result = $this->transport->delete($uri);

		return $result;
	}

	/**
	 * @param array $query
	 *
	 * @return ResourceCollection
	 */
	public function all($query = [])
	{
		if (null !== $this->collection && $this->query === $query)
		{
			return $this->collection;
		}

		$uri     = $this->getFullUri();
		$options = [] === $query ? [] : ['query' => $query];
		$data    = $this->transport->get($uri, null, $options);

		$this->query      = $options;
		$this->collection = $this->postAll($data);

		return $this->collection;
	}

	/** {@inheritdoc} */
	public function count()
	{
		$collection = $this->all();

		return count($collection);
	}

	/**
	 * @return ResourceCollection
	 */
	public function getIterator()
	{
		$collection = $this->all();

		return $collection;
	}

	/**
	 * @param array $data
	 *
	 * @return ResourceCollection
	 */
	protected function postAll(array $data)
	{
		$childResourceName = $this->getChildResourceName();

		foreach ($data as $key => $resourceData)
		{
			$data[$key] = $this->getObjectFromArray($resourceData[$childResourceName]);
		}

		return new ResourceCollection($data, $childResourceName);
	}
}
