<?php

namespace prgTW\BaseCRM\Resource;

use Doctrine\Common\Inflector\Inflector;
use prgTW\BaseCRM\Client;

abstract class Resource
{
	const ENDPOINT_APP    = 'https://app.futuresimple.com';
	const ENDPOINT_COMMON = 'https://common.futuresimple.com';
	const ENDPOINT_CORE   = 'https://core.futuresimple.com';
	const ENDPOINT_CRM    = 'https://crm.futuresimple.com';
	const ENDPOINT_LEADS  = 'https://leads.futuresimple.com';
	const ENDPOINT_SALES  = 'https://sales.futuresimple.com';
	const ENDPOINT_TAGS   = 'https://tags.futuresimple.com';

	const PREFIX = 'api/v1';

	/** @var array */
	protected $rawData = null;

	/** @var Client */
	protected $client;

	/** @var \Resource[] */
	protected $subResources = [];

	/** @var string */
	protected $uri;

	/**
	 * @param Client $client
	 * @param string $uri
	 * @param array  $data
	 */
	public function __construct(Client $client, $uri, array $data = [])
	{
		$this->client = $client;
		$this->uri    = $uri;
		if ([] !== $data)
		{
			$this->hydrate($data);
		}
	}

	/**
	 * @return string
	 */
	abstract protected function getEndpoint();

	/**
	 * @return string
	 */
	protected function getFullUri()
	{
		return sprintf('%s/%s/%s', static::getEndpoint(), static::PREFIX, $this->uri);
	}

	/**
	 * @param string $name
	 *
	 * @return Resource|\Resource[]
	 */
	public function getSubResources($name = null)
	{
		if (isset($name))
		{
			return isset($this->subResources[$name]) ? $this->subResources[$name] : null;
		}

		return $this->subResources;
	}

	/**
	 * @param string[] $resourceClassNames
	 *
	 * @throws \InvalidArgumentException when class name is not a Resource
	 */
	protected function setSubResources(array $resourceClassNames)
	{
		foreach ($resourceClassNames as $resourceClassName)
		{
			if (false === is_subclass_of($resourceClassName, Resource::class))
			{
				throw new \InvalidArgumentException(sprintf('Class %s is not an instance of %s', $resourceClassName, Resource::class));
			}
			$resourceName = $this->getResourceName($resourceClassName);
			$uri          = ltrim(sprintf('%s/%s', $this->uri, $resourceName), '/');
			$class        = new $resourceClassName($this->client, $uri);

			$this->subResources[lcfirst(Inflector::camelize($resourceName))] = $class;
		}
	}

	/**
	 * @param string $key
	 *
	 * @return mixed
	 */
	public function __get($key)
	{
		$subResource = $this->getSubResources($key);
		if (null !== $subResource)
		{
			return $subResource;
		}

		return $this->$key;
	}

	/**
	 * @param string $resourceClassName Fully classified class name
	 *
	 * @return string
	 */
	protected function getResourceName($resourceClassName = null)
	{
		$name = null === $resourceClassName ? static::class : $resourceClassName;
		$name = array_pop(explode('\\', $name));

		return Inflector::tableize($name);
	}

	/**
	 * @param array $data
	 */
	protected function hydrate(array $data)
	{
		$this->rawData = $data;
		$vars          = array_diff_key(get_class_vars(static::class), get_class_vars(get_parent_class($this)));

		foreach ($data as $name => $value)
		{
			$key = Inflector::camelize($name);
			if (array_key_exists($key, $vars))
			{
				$this->$key = $value;
			}
		}

		$this->postHydrate($data);
	}

	/**
	 * @return array
	 */
	protected function dehydrate()
	{
		$vars = array_diff_key(get_class_vars(static::class), get_class_vars(get_parent_class($this)));

		$data = [];
		foreach (array_keys($vars) as $key)
		{
			$data[Inflector::tableize($key)] = $this->$key;
		}

		$this->postDehydrate($data);

		return $data;
	}

	/**
	 * @param array $data
	 *
	 * @codeCoverageIgnore
	 */
	protected function postHydrate(array &$data)
	{

	}

	/**
	 * @param array $data
	 *
	 * @codeCoverageIgnore
	 */
	protected function postDehydrate(array &$data)
	{

	}
}
