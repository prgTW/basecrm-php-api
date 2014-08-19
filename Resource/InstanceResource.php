<?php

namespace prgTW\BaseCRM\Resource;

abstract class InstanceResource extends Resource
{
	/**
	 * @throws \InvalidArgumentException when resource key is not found in the response
	 * @return $this
	 */
	public function get()
	{
		$uri          = $this->getFullUri();
		$resourceName = $this->getResourceName();
		$data         = $this->client->get($uri, $resourceName);

		$this->hydrate($data);

		return $this;
	}

	/**
	 * @return $this
	 */
	public function save()
	{
		$resourceName = $this->getResourceName();
		$uri          = $this->getFullUri();
		$data         = $this->dehydrate();
		$response     = $this->client->put($uri, $resourceName, [
			'query' => [
				$resourceName => $data,
			],
		]);
		$this->hydrate($response);

		return $this;
	}
}
