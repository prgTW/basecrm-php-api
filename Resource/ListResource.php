<?php

namespace prgTW\BaseCRM\Resource;

use Doctrine\Common\Inflector\Inflector;
use prgTW\BaseCRM\Client\ClientInterface;
use prgTW\BaseCRM\Service\ResourceCollection;

abstract class ListResource extends Resource implements \IteratorAggregate, \Countable
{
	/** @var string */
	protected $instanceName;

	/** {@inheritdoc} */
	public function __construct(ClientInterface $client, $uri)
	{
		parent::__construct($client, $uri);
		$this->instanceName = Inflector::singularize(static::class);
	}

	/**
	 * @return ResourceCollection
	 */
	public function get()
	{
		$singleResourceName = Inflector::singularize($this->getResourceName());
		$uri                = $this->getFullUri();
		$data               = $this->client->get($uri);

		foreach ($data as $key => $resourceData)
		{
			$data[$key] = $this->getObjectFromJson($resourceData[$singleResourceName]);
		}

		return new ResourceCollection($data, $singleResourceName);
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

		return new $this->instanceName($this->client, $uri, $params);
	}

	/** @{inheritdoc} */
	public function count()
	{
		$collection = $this->get();

		return $collection->count();
	}

	/**
	 * @return ResourceCollection
	 */
	public function getIterator()
	{
		$collection = $this->get();

		return $collection;
	}
}
