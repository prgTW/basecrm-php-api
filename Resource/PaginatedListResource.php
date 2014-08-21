<?php

namespace prgTW\BaseCRM\Resource;

use Doctrine\Common\Inflector\Inflector;

abstract class PaginatedListResource extends ListResource
{
	/**
	 * @param int    $page
	 * @param string $sortBy
	 *
	 * @return Page
	 */
	public function all($page = 1, $sortBy = null)
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

		$data = $this->transport->get($uri, null, $query);

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
		return new AutoPagingIterator([$this, 'all'], $page, $sortBy);
	}
}
