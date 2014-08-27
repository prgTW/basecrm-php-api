<?php

namespace prgTW\BaseCRM\Tests\Service;

use prgTW\BaseCRM\BaseCrm;
use prgTW\BaseCRM\Client\GuzzleClient;
use prgTW\BaseCRM\Resource\Resource;
use prgTW\BaseCRM\Resource\ResourceCollection;
use prgTW\BaseCRM\Service\Enum\LeadsSortBy;
use prgTW\BaseCRM\Service\Lead;
use prgTW\BaseCRM\Tests\AbstractTest;

class LeadsTest extends AbstractTest
{
	public function testGet()
	{
		$client = \Mockery::mock(GuzzleClient::class);
		$client
			->shouldReceive('request')
			->once()
			->with('GET', sprintf('%s/%s/leads.json', Resource::ENDPOINT_LEADS, Resource::PREFIX), $this->getQuery([
				'query' => [
					'page' => 1
				]
			]))
			->andReturn($this->getResponse(200, '
				{
					"success": true,
					"metadata": {
						"count": 2
					},
					"items": [
						{
							"success": true,
							"lead": {
								"id": 1,
								"user_id": 2,
								"account_id": 3,
								"owner_id": 2,
								"first_name": "Lead",
								"last_name": "One",
								"company_name": null,
								"created_at": "2013-04-10T15:04:24+00:00",
								"state": null,
								"display_name": "Lead One",
								"conversion_name": "Lead One",
								"added_on": "2013-04-10T15:04:24+00:00"
							},
							"metadata": {
							}
						},
						{
							"success": true,
							"lead": {
								"id": 2,
								"user_id": 2,
								"account_id": 3,
								"owner_id": 2,
								"first_name": "Lead",
								"last_name": "Two",
								"company_name": null,
								"created_at": "2013-04-10T15:04:00+00:00",
								"state": null,
								"display_name": "Lead Two",
								"conversion_name": "Lead Two",
								"added_on": "2013-04-10T15:04:00+00:00"
							},
							"metadata": {
							}
						}
					]
				}
			'));

		$baseCrm = new BaseCrm('', $client);
		$leads   = $baseCrm->getLeads();
		$found   = 0;
		/** @var Lead $lead */
		foreach ($leads as $lead)
		{
			$this->assertInstanceOf(Lead::class, $lead);
			$this->assertEquals($found + 1, $lead->id);
			++$found;
		}
		$this->assertEquals(2, $found);
	}

	/**
	 * @param LeadsSortBy $sortBy
	 *
	 * @dataProvider provideSortFields
	 */
	public function testSorting(LeadsSortBy $sortBy = null)
	{
		$query = $this->getQuery([
			'query' => [
				'page' => 1
			]
		]);
		if (null !== $sortBy)
		{
			$query['query']['sort_by'] = $sortBy->getValue();
		}
		$client = \Mockery::mock(GuzzleClient::class);
		$client
			->shouldReceive('request')
			->once()
			->with('GET', sprintf('%s/%s/leads.json', Resource::ENDPOINT_LEADS, Resource::PREFIX), $query)
			->andReturn($this->getResponse(200, '
				{
					"success": true,
					"metadata": {
						"count": 0
					},
					"items": [
					]
				}
			'));

		$baseCrm = new BaseCrm('', $client);
		$baseCrm->getLeads()->all(1, $sortBy);
	}

	/**
	 * @return array
	 */
	public function provideSortFields()
	{
		return [
			[null],
			[LeadsSortBy::ID_DESC()],
			[LeadsSortBy::ADDED_ON()],
			[LeadsSortBy::FIRST_NAME()],
			[LeadsSortBy::LAST_NAME()],
		];
	}

	public function testAutoPagination()
	{
		$client = \Mockery::mock(GuzzleClient::class);
		$client
			->shouldReceive('request')
			->twice()
			->with('GET', sprintf('%s/%s/leads.json', Resource::ENDPOINT_LEADS, Resource::PREFIX), \Mockery::any())
			->andReturn($this->getResponse(200, '
				{
					"success": true,
					"metadata": {
						"count": 1
					},
					"items": [
						{
							"success": true,
							"lead": {
								"id": 1
							},
							"metadata": {
							}
						}
					]
				}
			'));
		$client
			->shouldReceive('request')
			->once()
			->with('GET', sprintf('%s/%s/leads.json', Resource::ENDPOINT_LEADS, Resource::PREFIX), $this->getQuery([
				'query' => [
					'page' => 3
				]
			]))
			->andReturn($this->getResponse(200, '
				{
					"success": true,
					"metadata": {
						"count": 0
					},
					"items": [
					]
				}
			'));

		$baseCrm = new BaseCrm('', $client);
		$leads   = $baseCrm->getLeads();
		$leads->setAutoPagination(true);
		$found = 0;
		/** @var Lead $lead */
		foreach ($leads as $lead)
		{
			$this->assertInstanceOf(Lead::class, $lead);
			++$found;
		}
		$this->assertEquals(2, $found);
	}

	public function testTaggings()
	{
		$client = \Mockery::mock(GuzzleClient::class);
		$client
			->shouldReceive('request')
			->once()
			->with('GET', sprintf('%s/%s/leads/1.json', Resource::ENDPOINT_LEADS, Resource::PREFIX), $this->getQuery())
			->andReturn($this->getResponse(200, '
				{
					"success": true,
					"lead": {
						"id": 1,
						"user_id": 2,
						"account_id": 3,
						"owner_id": 2,
						"first_name": "Lead",
						"last_name": "One",
						"company_name": null,
						"created_at": "2013-04-10T15:04:24+00:00",
						"state": null,
						"display_name": "Lead One",
						"conversion_name": "Lead One",
						"added_on": "2013-04-10T15:04:24+00:00"
					},
					"metadata": {
					}
				}
			'));
		$baseCrm = new BaseCrm('', $client);
		/** @var Lead $lead */
		$lead = $baseCrm->getLeads()->get(1);
		$this->assertInstanceOf(Lead::class, $lead);
		$this->assertEquals(1, $lead->id);

		$client
			->shouldReceive('request')
			->once()
			->with('POST', sprintf('%s/%s/taggings.json', Resource::ENDPOINT_TAGS, Resource::PREFIX), $this->getQuery([
				'query' => [
					'app_id'        => 5,
					'taggable_type' => 'Lead',
					'taggable_id'   => 1,
					'tag_list'      => 'tag1,tag2',
				],
			]))
			->andReturn($this->getResponse(200, '
				[
					{
						"tag": {
							"id": 1,
							"name": "tag1",
							"permissions_holder_id": 20
						}
					},
					{
						"tag": {
							"id": 2,
							"name": "tag2",
							"permissions_holder_id": 20
						}
					}
				]
			'));

		$tags = $lead->saveTags(['tag1', 'tag2']);
		$this->assertInstanceOf(ResourceCollection::class, $tags);
		$this->assertCount(2, $tags);
	}
}
