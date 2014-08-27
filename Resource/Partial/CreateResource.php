<?php

namespace prgTW\BaseCRM\Resource\Partial;

use prgTW\BaseCRM\Resource\DetachedResource;
use prgTW\BaseCRM\Resource\ResourceCollection;
use prgTW\BaseCRM\Transport\Transport;

/**
 * @property Transport transport
 * @method string getResourceName($resourceClassName = null)
 * @method string getChildResourceName($resourceClassName = null)
 * @method string getObjectFromArray(array $params, $instanceClassName = null, $idParam = 'id')
 * @method string getFullUri($suffix = '')
 */
trait CreateResource
{
	/**
	 * @param DetachedResource $resource
	 * @param bool             $useKey
	 *
	 * @throws \InvalidArgumentException on resource scopes mismatch
	 * @return Resource|ResourceCollection
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
			$data = $this->getObjectFromArray($data[$childResourceName]);
		}
		else
		{
			$data = array_map([$this, 'getObjectFromArray'], $data);

			return new ResourceCollection($data);
		}

		return $data;
	}
}
