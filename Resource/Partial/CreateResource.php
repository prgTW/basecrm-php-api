<?php

namespace prgTW\BaseCRM\Resource\Partial;

use prgTW\BaseCRM\Resource\DetachedResource;

trait CreateResource
{
	/**
	 * @param DetachedResource $resource
	 *
	 * @return InstanceResource
	 * @throws \InvalidArgumentException on resource scopes mismatch
	 */
	public function create(DetachedResource $resource)
	{
		$newResourceName   = $resource->getResourceName();
		$childResourceName = $this->getChildResourceName();
		if ($newResourceName !== $childResourceName)
		{
			//@codeCoverageIgnoreStart
			throw new \InvalidArgumentException(sprintf('Cannot create resource "%s" under resource "%s"', $newResourceName, $this->getResourceName()));
			//@codeCoverageIgnoreEnd
		}

		$uri     = $this->getFullUri();
		$options = [
			'query' => [
				$childResourceName => $resource->dehydrate(),
			],
		];
		$data    = $this->transport->post($uri, $childResourceName, $options);

		$resource = $this->getObjectFromJson($data);

		return $resource;
	}
}
