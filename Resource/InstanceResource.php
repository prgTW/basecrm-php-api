<?php

namespace prgTW\BaseCRM\Resource;

use prgTW\BaseCRM\Exception\ResourceException;

abstract class InstanceResource extends LazyLoadedResource
{
	/**
	 * @throws \InvalidArgumentException when resource key is not found in the response
	 * @return $this
	 */
	public function get()
	{
		$uri          = $this->getFullUri();
		$resourceName = $this->getResourceName();
		$data         = $this->transport->get($uri, $resourceName);

		$this->hydrate($data);

		return $this;
	}

	/**
	 * @param array $fieldNames
	 *
	 * @throws ResourceException when there's no data to save
	 * @return $this
	 */
	public function save(array $fieldNames = [])
	{
		if ([] === $fieldNames)
		{
			$fieldNames = array_keys($this->data);
			if ([] === $fieldNames)
			{
				throw new ResourceException('No data to save');
			}

			return $this->save($fieldNames);
		}
		$resourceName = $this->getResourceName();
		$uri          = $this->getFullUri();
		$data         = $this->dehydrate($fieldNames);
		$response     = $this->transport->put($uri, $resourceName, [
			'query' => [
				$resourceName => $data,
			],
		]);
		$this->hydrate($response);

		return $this;
	}

	/** {@inheritdoc} */
	protected function lazyLoad()
	{
		$this->get();
	}
}
