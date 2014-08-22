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
	 * @throws ResourceException when dehydration has been stopped
	 * @return array
	 */
	public function dehydrate()
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
			foreach ($this->data as $key => $value)
			{
				$getter     = sprintf('get%s', ucfirst(Inflector::camelize($key)));
				$data[$key] = method_exists($this, $getter) ? $this->$getter() : $this->$key;
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
