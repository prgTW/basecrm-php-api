<?php

namespace prgTW\BaseCRM\Resource;

use Doctrine\Common\Inflector\Inflector;
use prgTW\BaseCRM\Transport\Transport;

abstract class Resource extends BaseResource
{
	/** @var \Resource[] */
	protected $subResources = [];

	/** @var Transport */
	protected $transport;

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

		$this->init();
	}

	/**
	 * @return string
	 */
	abstract protected function getEndpoint();

	protected function init()
	{

	}

	/**
	 * @param string $suffix
	 *
	 * @return string
	 */
	protected function getFullUri($suffix = '')
	{
		return sprintf('%s/%s/%s%s', static::getEndpoint(), static::PREFIX, $this->uri, $suffix);
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
		foreach ($resourceClassNames as $key => $value)
		{
			$resourceClassName = is_int($key) ? $value : $key;
			if (false === is_subclass_of($resourceClassName, Resource::class))
			{
				//@codeCoverageIgnoreStart
				throw new \InvalidArgumentException(sprintf('Class %s is not an instance of %s', $resourceClassName, Resource::class));
				//@codeCoverageIgnoreEnd
			}
			$resourceName = $this->getResourceName($resourceClassName);
			$uri          = ltrim(sprintf('%s/%s', $this->uri, $resourceName), '/');
			if (false === is_int($key))
			{
				$uri = $value;
			}

			$class = new $resourceClassName($this->transport, $uri);

			$this->subResources[lcfirst(Inflector::camelize($resourceName))] = $class;
		}
	}

	/**
	 * @param array  $params
	 * @param string $instanceClassName
	 * @param string $idParam
	 *
	 * @return Resource
	 */
	protected function getObjectFromArray(array $params, $instanceClassName = null, $idParam = 'id')
	{
		if (null === $instanceClassName)
		{
			$instanceClassName = Inflector::singularize(static::class);
		}

		if (array_key_exists($idParam, $params))
		{
			$uri = sprintf('%s/%s', $this->uri, $params[$idParam]);
		}
		else
		{
			$uri = $this->uri;
		}

		return new $instanceClassName($this->transport, $uri, $params);
	}

	/**
	 * @param $method
	 * @param $arguments
	 *
	 * @return Resource|ResourceCollection
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
	 * @param string $name
	 *
	 * @return mixed
	 */
	public function __get($name)
	{
		$getter = sprintf('get%s', ucfirst(Inflector::camelize($name)));
		if (method_exists($this, $getter))
		{
			return $this->$getter();
		}

		$key = Inflector::tableize($name);
		if (array_key_exists($key, $this->data))
		{
			return $this->data[$key];
		}

		return $this->$name;
	}

	/**
	 * @param string $name
	 * @param mixed  $value
	 */
	public function __set($name, $value)
	{
		$setter = sprintf('set%s', ucfirst(Inflector::camelize($name)));
		if (method_exists($this, $setter))
		{
			return $this->$setter($value);
		}

		$key              = Inflector::tableize($name);
		$this->data[$key] = $value;
	}
}
