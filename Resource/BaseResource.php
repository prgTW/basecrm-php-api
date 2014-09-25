<?php

namespace prgTW\BaseCRM\Resource;

use Doctrine\Common\Inflector\Inflector;
use prgTW\BaseCRM\Exception\ResourceException;

abstract class BaseResource
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
	protected $data = null;

	/**
	 * @param string $resourceClassName Fully classified class name
	 *
	 * @return string
	 */
	protected function getClassNameOnly($resourceClassName = null)
	{
		$name = null === $resourceClassName ? static::class : $resourceClassName;
		$name = explode('\\', $name);
		$name = end($name);

		return $name;
	}

	/**
	 * @param string $resourceClassName Fully classified class name
	 *
	 * @return string
	 */
	public function getResourceName($resourceClassName = null)
	{
		$name = $this->getClassNameOnly($resourceClassName);

		return Inflector::tableize($name);
	}

	/**
	 * @param string $resourceClassName Fully classified class name
	 *
	 * @return string
	 */
	protected function getChildResourceName($resourceClassName = null)
	{
		$resourceName = $this->getResourceName($resourceClassName);
		$singular     = Inflector::singularize($resourceName);

		return $singular;
	}

	/**
	 * @param array $data
	 */
	public function hydrate(array $data)
	{
		$this->data = [];
		foreach ($data as $key => $value)
		{
			$setter = sprintf('set%s', ucfirst(Inflector::camelize($key)));
			if (method_exists($this, $setter))
			{
				$this->$setter($value);
			}
			else
			{
				$this->data[$key] = $value;
			}
		}

		$this->postHydrate($data);
	}

	/**
	 * @param array $fieldNames
	 *
	 * @throws ResourceException when dehydration has been stopped
	 * @return array
	 */
	public function dehydrate(array $fieldNames = [])
	{
		$result = $this->preDehydrate($fieldNames);
		if (false === $result)
		{
			//@codeCoverageIgnoreStart
			throw new ResourceException('Dehydration has been stopped');
			//@codeCoverageIgnoreEnd
		}

		if (is_array($result))
		{
			$fieldNames = $result;
		}

		$data = [];
		if ([] === $fieldNames)
		{
			$data = $this->data;
		}
		else
		{
			foreach ($fieldNames as $fieldName)
			{
				$getter           = sprintf('get%s', ucfirst(Inflector::camelize($fieldName)));
				$data[$fieldName] = method_exists($this, $getter) ? $this->$getter() : $this->data[$fieldName];
			}
		}

		$this->postDehydrate($data);

		return $data;
	}

	/**
	 * @param string $name
	 *
	 * @return bool
	 */
	public function __isset($name)
	{
		$issetter = sprintf('isset%s', ucfirst(Inflector::camelize($name)));
		if (method_exists($this, $issetter))
		{
			return $this->$issetter();
		}

		$key = Inflector::tableize($name);
		if (array_key_exists($key, $this->data))
		{
			return true;
		}

		return isset($this->$name);
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

	/**
	 * @param array $data
	 *
	 * @codeCoverageIgnore
	 */
	protected function postHydrate(array $data)
	{

	}

	/**
	 * @param array $fieldNames
	 *
	 * @return array|bool False: stop dehydration, array: new field names
	 *
	 * @codeCoverageIgnore
	 */
	protected function preDehydrate(array $fieldNames)
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
