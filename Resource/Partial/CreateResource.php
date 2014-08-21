<?php

namespace prgTW\BaseCRM\Resource\Partial;

use prgTW\BaseCRM\Resource\DetachedResource;
use prgTW\BaseCRM\Resource\InstanceResource;
use prgTW\BaseCRM\Resource\ListResource;
use prgTW\BaseCRM\Resource\ResourceCollection;

trait CreateResource
{
	/**
	 * @param DetachedResource $resource
	 * @param bool             $useKey
	 *
	 * @throws \InvalidArgumentException on resource scopes mismatch
	 * @return InstanceResource|ListResource
	 */
	public function create(DetachedResource $resource, $useKey = true)
	{
		$newResourceName   = $resource->getResourceName();
		$childResourceName = $this->getChildResourceName();
		if ($newResourceName !== $childResourceName)
		{
			//@codeCoverageIgnoreStart
			throw new \InvalidArgumentException(sprintf('Cannot create resource "%s" under resource "%s"', $newResourceName, $this->getResourceName()));
			//@codeCoverageIgnoreEnd
		}

		$uri        = $this->getFullUri();
		$options    = [];
		$dehydrated = $resource->dehydrate();
		if (true === $useKey)
		{
			$options['query'] = [$childResourceName => $dehydrated];
		}
		else
		{
			$options['query'] = $dehydrated;
		}
		$data = $this->transport->post($uri, null, $options);

		if (array_key_exists($childResourceName, $data))
		{
			$data = $this->getObjectFromJson($data[$childResourceName]);
		}
		else
		{
			$data = array_map([$this, 'getObjectFromJson'], $data);

			return new ResourceCollection($data);
		}

		return $data;
	}
}
