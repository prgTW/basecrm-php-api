<?php

namespace prgTW\BaseCRM\Resource;

use Doctrine\Common\Inflector\Inflector;
use prgTW\BaseCRM\Utils\Convert;

abstract class PaginatedListResource extends ListResource
{
	/**
	 * @param int    $page
	 * @param string $sortBy
	 *
	 * @return Page
	 */
	public function get($page = 1, $sortBy = null)
	{
		$singleResourceName = Inflector::singularize($this->getResourceName());
		$uri                = $this->getFullUri();
		$query              = [
			'query' => [
				'page' => $page,
			],
		];
		if (null !== $sortBy)
		{
			$query['query']['sort_by'] = $sortBy;
		}

		$data = $this->client->get($uri, null, $query);
		$data = Convert::objectToArray($data);

		foreach ($data['items'] as $key => $resourceData)
		{
			$data['items'][$key] = $this->getObjectFromJson($resourceData[$singleResourceName]);
		}

		return new Page($data, $singleResourceName);
	}

	/** {@inheritdoc} */
	public function count()
	{
		throw new \BadMethodCallException('Not allowed');
	}

	/**
	 * @param int    $page
	 * @param string $sortBy
	 *
	 * @return AutoPagingIterator
	 */
	public function getIterator($page = 1, $sortBy = null)
	{
		return new AutoPagingIterator([$this, 'get'], $page, $sortBy);
	}
}
