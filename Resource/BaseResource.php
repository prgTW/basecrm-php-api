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
	protected $rawData = null;

	/**
	 * @param string $resourceClassName Fully classified class name
	 *
	 * @return string
	 */
	public function getResourceName($resourceClassName = null)
	{
		$name = null === $resourceClassName ? static::class : $resourceClassName;
		$name = explode('\\', $name);
		$name = end($name);

		return Inflector::tableize($name);
	}

	/**
	 * @param array $data
	 */
	public function hydrate(array $data)
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
