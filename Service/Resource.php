<?php

namespace prgTW\BaseCRM\Service;

use prgTW\BaseCRM\Client;
use prgTW\BaseCRM\Utils\Inflector;

abstract class Resource
{
	const ENDPOINT_APP    = 'https://app.futuresimple.com';
	const ENDPOINT_COMMON = 'https://common.futuresimple.com';
	const ENDPOINT_CORE   = 'https://core.futuresimple.com';
	const ENDPOINT_CRM    = 'https://crm.futuresimple.com';
	const ENDPOINT_LEADS  = 'https://leads.futuresimple.com';
	const ENDPOINT_SALES  = 'https://sales.futuresimple.com';
	const ENDPOINT_TAGS   = 'https://tags.futuresimple.com';

	const PREFIX  = 'api/v1';

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

	protected function getFullUri()
	{
		return sprintf('%s/%s/%s', rtrim(static::getEndpoint(), '/'), static::PREFIX, ltrim($this->uri, '/'));
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
	 * @param string[] $resourceNames
	 */
	protected function setSubResources(array $resourceNames)
	{
		$namespace = substr(self::class, 0, strrpos(self::class, '\\'));
		foreach ($resourceNames as $resourceName)
		{
			$className                         = ucfirst(Inflector::camelize($resourceName));
			$fqn                               = sprintf('%s\\Rest\\%s', $namespace, $className);
			$uri                               = sprintf('%s/%s', $this->uri, Inflector::underscore($className));
			$class                             = new $fqn($this->client, $uri);
			$this->subResources[$resourceName] = $class;
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
