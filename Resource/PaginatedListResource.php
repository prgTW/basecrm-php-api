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
		$query = [
			'page' => $page,
		];
		if (null !== $sortBy)
		{
			$query['sort_by'] = $sortBy;
		}

		return parent::all($query);
	}

	/** {@inheritdoc} */
	protected function postAll(array $data)
	{
		$singleResourceName = Inflector::singularize($this->getResourceName());

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
