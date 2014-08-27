<?php

namespace prgTW\BaseCRM\Service;

use prgTW\BaseCRM\Resource\DetachedResource;
use prgTW\BaseCRM\Resource\Page;
use prgTW\BaseCRM\Resource\PaginatedListResource;
use prgTW\BaseCRM\Service\Enum\DealStage;

class Deals extends PaginatedListResource
{
	/** {@inheritdoc} */
	protected function getEndpoint()
	{
		return self::ENDPOINT_SALES;
	}

	/**
	 * @param int       $page
	 * @param DealStage $stage defaults to DealStage::INCOMING
	 *
	 * @return Page
	 */
	public function all($page = 1, DealStage $stage = null)
	{
		$stage = $stage ? : DealStage::INCOMING();
		$query = [
			'stage' => $stage->getValue(),
		];

		return parent::getPage($page, $query);
	}

	/** {@inheritdoc} */
	public function create(DetachedResource $resource, array $fieldNames = [], $useKey = true)
	{
		$fieldNames = array_intersect_key($fieldNames, [
			'name',
			'entity_id',
			'scope',
			'hot',
			'deal_tags',
			'contact_ids',
			'source_id',
			'stage',
		]);

		return parent::create($resource, $fieldNames, $useKey);
	}
}
