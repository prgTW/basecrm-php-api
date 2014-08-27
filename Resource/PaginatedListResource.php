<?php

namespace prgTW\BaseCRM\Resource;

abstract class PaginatedListResource extends ListResource
{
	/** @var bool */
	protected $autoPagination = false;

	/**
	 * @return bool
	 */
	public function getAutoPagination()
	{
		return $this->autoPagination;
	}

	/**
	 * @param bool $autoPagination
	 *
	 * @return $this
	 */
	public function setAutoPagination($autoPagination)
	{
		$this->autoPagination = $autoPagination;

		return $this;
	}

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

		if (array_key_exists('items', $data))
		{
			foreach ($data['items'] as $key => $resourceData)
			{
				$data['items'][$key] = $this->getObjectFromArray($resourceData[$childResourceName]);
			}
		}
		else
		{
			foreach ($data as $key => $resourceData)
			{
				$data[$key] = $this->getObjectFromArray($resourceData[$childResourceName]);
			}
		}

		return new Page($data);
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
	 * @return AutoPagingIterator|PagingIterator
	 */
	public function getIterator($page = 1, array $query = [])
	{
		$iteratorClass = true === $this->autoPagination ? AutoPagingIterator::class : PagingIterator::class;

		return new $iteratorClass([$this, 'getPage'], $page, $query);
	}
}
