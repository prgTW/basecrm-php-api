<?php

namespace prgTW\BaseCRM\Resource;

use Doctrine\Common\Inflector\Inflector;
use prgTW\BaseCRM\Transport\Transport;

abstract class ListResource extends Resource implements \IteratorAggregate, \Countable
{
	/** @var string */
	protected $instanceName;

	/** {@inheritdoc} */
	public function __construct(Transport $transport, $uri)
	{
		parent::__construct($transport, $uri);
		$this->instanceName = Inflector::singularize(static::class);
	}

	/**
	 * @param string $id
	 *
	 * @return InstanceResource
	 */
	public function get($id)
	{
		$uri = sprintf('%s/%s', $this->uri, $id);
		/** @var InstanceResource $resource */
		$resource = new $this->instanceName($this->transport, $uri);
		$resource->get();

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
	public function all(array $query = [])
	{
		$singleResourceName = Inflector::singularize($this->getResourceName());
		$uri                = $this->getFullUri();
		$options            = [] === $query ? [] : ['query' => $query];
		$data               = $this->transport->get($uri, null, $options);

		$data = $this->postAll($data);

		return $data;
	}

	/**
	 * @param array  $params
	 * @param string $idParam
	 *
	 * @return Resource
	 */
	protected function getObjectFromJson(array $params, $idParam = 'id')
	{
		if (array_key_exists($idParam, $params))
		{
			$uri = sprintf('%s/%s', $this->uri, $params[$idParam]);
		}
		else
		{
			$uri = $this->uri;
		}

		return new $this->instanceName($this->transport, $uri, $params);
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
		$singleResourceName = Inflector::singularize($this->getResourceName());

		foreach ($data as $key => $resourceData)
		{
			$data[$key] = $this->getObjectFromJson($resourceData[$singleResourceName]);
		}

		return new ResourceCollection($data, $singleResourceName);
	}
}
