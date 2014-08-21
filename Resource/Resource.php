<?php

namespace prgTW\BaseCRM\Resource;

use Doctrine\Common\Inflector\Inflector;
use prgTW\BaseCRM\Exception\ResourceException;
use prgTW\BaseCRM\Transport\Transport;

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

	/** @var Transport */
	protected $transport;

	/** @var \Resource[] */
	protected $subResources = [];

	/** @var string */
	protected $uri;

	/**
	 * @param Transport $transport
	 * @param string    $uri
	 * @param array     $data
	 */
	public function __construct(Transport $transport, $uri, array $data = [])
	{
		$this->transport = $transport;
		$this->uri       = $uri;
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
				//@codeCoverageIgnoreStart
				throw new \InvalidArgumentException(sprintf('Class %s is not an instance of %s', $resourceClassName, Resource::class));
				//@codeCoverageIgnoreEnd
			}
			$resourceName = $this->getResourceName($resourceClassName);
			$uri          = ltrim(sprintf('%s/%s', $this->uri, $resourceName), '/');
			$class        = new $resourceClassName($this->transport, $uri);

			$this->subResources[lcfirst(Inflector::camelize($resourceName))] = $class;
		}
	}

	/**
	 * @param $method
	 * @param $arguments
	 *
	 * @return Resource|\Resource[]
	 * @throws \BadMethodCallException
	 */
	public function __call($method, $arguments)
	{
		if ('get' !== substr($method, 0, 3))
		{
			//@codeCoverageIgnoreStart
			throw new \BadMethodCallException('Unknown resource');
			//@codeCoverageIgnoreEnd
		}

		$key         = lcfirst(substr($method, 3));
		$subResource = $this->getSubResources($key);
		if (null === $subResource)
		{
			//@codeCoverageIgnoreStart
			throw new \BadMethodCallException(sprintf('Resource "%s" not found', $key));
			//@codeCoverageIgnoreEnd
		}

		return $subResource;
	}

	/**
	 * @param string $resourceClassName Fully classified class name
	 *
	 * @return string
	 */
	protected function getResourceName($resourceClassName = null)
	{
		$name = null === $resourceClassName ? static::class : $resourceClassName;
		$name = explode('\\', $name);
		$name = end($name);

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
	 * @throws ResourceException when dehydration has been stopped
	 * @return array
	 */
	protected function dehydrate()
	{
		$data = $this->preDehydrate();
		if (false === $data)
		{
			//@codeCoverageIgnoreStart
			throw new ResourceException('Dehydration has been stopped');
			//@codeCoverageIgnoreEnd
		}

		if (false === is_array($data))
		{
			$vars = array_diff_key(get_class_vars(static::class), get_class_vars(get_parent_class($this)));
			$data = [];
			foreach (array_keys($vars) as $key)
			{
				$data[Inflector::tableize($key)] = $this->$key;
			}
		}

		$this->postDehydrate($data);

		return $data;
	}

	/**
	 * @param array $data
	 *
	 * @codeCoverageIgnore
	 */
	protected function postHydrate(array $data)
	{

	}

	/**
	 * @return array|bool False: stop dehydration, array: deserialized data
	 *
	 * @codeCoverageIgnore
	 */
	protected function preDehydrate()
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
