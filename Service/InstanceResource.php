<?php

namespace prgTW\BaseCRM\Service;

use Doctrine\Common\Inflector\Inflector;

class InstanceResource extends Resource
{
	/**
	 * @return $this
	 */
	public function get()
	{
		$uri      = $this->getFullUri();
		$response = $this->client->get($uri);
		$this->hydrate($response);

		return $this;
	}

	/**
	 * @return $this
	 */
	public function save()
	{
		$uri      = $this->getFullUri();
		$data     = $this->dehydrate();
		$response = $this->client->put($uri, [
			'query' => $data,
		]);
		$this->hydrate($response);

		return $this;
	}

	/**
	 * @param array $data
	 */
	protected function hydrate(array $data)
	{
		$key = Inflector::tableize(substr(static::class, strrpos(static::class, '\\') + 1));
		if (false === array_key_exists($key, $data))
		{
			return;
		}

		$vars = array_diff_key(get_class_vars(static::class), get_class_vars(get_parent_class($this)));

		foreach ($data[$key] as $name => $value)
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
		$domain = $key = Inflector::tableize(substr(static::class, strrpos(static::class, '\\') + 1));;
		$vars = array_diff_key(get_class_vars(static::class), get_class_vars(get_parent_class($this)));

		$data = [$domain => []];
		foreach (array_keys($vars) as $key)
		{
			$data[$domain][Inflector::tableize($key)] = $this->$key;
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
