<?php

namespace prgTW\BaseCRM\Resource;

use prgTW\BaseCRM\Exception\ResourceException;
use prgTW\BaseCRM\Service\Behavior\CustomFieldsTrait;
use prgTW\BaseCRM\Service\Enum\CustomFields;

abstract class InstanceResource extends LazyLoadedResource
{
	use CustomFieldsTrait;

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
				//@codeCoverageIgnoreStart
				throw new ResourceException('No data to save');
				//@codeCoverageIgnoreEnd
			}

			return $this->save($fieldNames);
		}
		$resourceName = $this->getResourceName();
		$uri          = $this->getFullUri();
		$data         = $this->dehydrate($fieldNames);

		if (in_array(CustomFieldsTrait::class, class_uses($this)))
		{
			/** @noinspection PhpUndefinedMethodInspection */
			$customFields = $this->getCustomFields()->toArray();
			if ([] !== $customFields)
			{
				$data[CustomFields::KEY_SAVING] = $customFields;
			}
		}

		$response = $this->transport->put($uri, $resourceName, [
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
