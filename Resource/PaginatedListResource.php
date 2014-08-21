<?php

namespace prgTW\BaseCRM\Resource;

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
		$childResourceName = $this->getChildResourceName();

		foreach ($data['items'] as $key => $resourceData)
		{
			$data['items'][$key] = $this->getObjectFromJson($resourceData[$childResourceName]);
		}

		return new Page($data, $childResourceName);
	}


	/** {@inheritdoc} */
	public function count()
	{
		//@codeCoverageIgnoreStart
		throw new \BadMethodCallException('Not allowed');
		//@codeCoverageIgnoreEnd
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
