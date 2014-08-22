<?php

namespace prgTW\BaseCRM\Resource;

abstract class PaginatedListResource extends ListResource
{
	/**
	 * @param int   $page
	 * @param array $query
	 *
	 * @return Page
	 */
	public function getPage($page = 1, array $query = [])
	{
		$query = array_merge_recursive($query, [
			'page' => $page,
		]);

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
	 * @param int   $page
	 * @param array $query
	 *
	 * @return AutoPagingIterator
	 */
	public function getIterator($page = 1, array $query = [])
	{
		return new AutoPagingIterator([$this, 'getPage'], $page, $query);
	}
}
