<?php

namespace prgTW\BaseCRM\Resource;

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
	 * @return $this
	 */
	public function save()
	{
		$resourceName = $this->getResourceName();
		$uri          = $this->getFullUri();
		$data         = $this->dehydrate();
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
