<?php

namespace prgTW\BaseCRM\Service;

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

	/** @var Client */
	protected $client;

	/** @var Resource[] */
	protected $subResources = [];

	/** @var string */
	protected $uri;

	public function __construct(Client $client, $uri)
	{
		$this->client = $client;
		$this->uri    = $uri;
	}

	/**
	 * @return string
	 */
	protected function getEndpoint()
	{
		throw new \LogicException('This method must be implemented in deriving class');
	}

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
	 * @return Resource|Resource[]
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
			$className                      = lcfirst(array_pop(explode('\\', $resourceClassName)));
			$uri                            = ltrim(sprintf('%s/%s', $this->uri, Inflector::tableize($className)), '/');
			$class                          = new $resourceClassName($this->client, $uri);
			$this->subResources[$className] = $class;
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
}
