<?php

namespace prgTW\BaseCRM\Tests\Service;

use prgTW\BaseCRM\BaseCrm;
use prgTW\BaseCRM\Client\GuzzleClient;
use prgTW\BaseCRM\Resource\CustomFieldsCollection;
use prgTW\BaseCRM\Resource\Resource;
use prgTW\BaseCRM\Service\Detached\Source as DetachedSource;
use prgTW\BaseCRM\Service\Source;
use prgTW\BaseCRM\Tests\AbstractTest;

class SourcesTest extends AbstractTest
{
	public function testAll()
	{
		$client = \Mockery::mock(GuzzleClient::class);
		$client
			->shouldReceive('request')
			->once()
			->with('GET', sprintf('%s/%s/sources.json', Resource::ENDPOINT_SALES, Resource::PREFIX), $this->getQuery())
			->andReturn($this->getResponse(200, '
				[
					{
					"source": {
						"name": "test",
						"id": 1
					}
				},
				{
					"source": {
						"name": "test",
						"id": 2
					}
				},
				{
					"source": {
						"name": "test",
						"id": 3
					}
				}
				]
			'));
		$baseCrm = new BaseCrm('', $client);
		$sources = $baseCrm->getSources();
		$this->assertCount(3, $sources);

		$i = 1;
		/** @var Source $source */
		foreach ($sources as $source)
		{
			$this->assertInstanceOf(Source::class, $source);
			$this->assertEquals($i, $source->id);
			$this->assertEquals('test', $source->name);

			$client
				->shouldReceive('request')
				->once()
				->with('PUT', sprintf('%s/%s/sources/%d.json', Resource::ENDPOINT_SALES, Resource::PREFIX, $source->id), $this->getQuery([
					'query' => [
						'source' => [
							'name' => 'test',
						],
					],
				]))
				->andReturn($this->getResponse(200, '
					{
						"source": {
							"name": "test",
							"id": ' . $i . '
						}
					}
				'));
			$source->save([
				'name',
			]);
			++$i;
		}
	}

	public function testAllWithOther()
	{
		$client = \Mockery::mock(GuzzleClient::class);
		$client
			->shouldReceive('request')
			->once()
			->with('GET', sprintf('%s/%s/sources.json', Resource::ENDPOINT_SALES, Resource::PREFIX), $this->getQuery([
				'query' => [
					'other' => 1,
				]
			]))
			->andReturn($this->getResponse(200, '
				[
					{
					"source": {
						"name": "test",
						"id": 1
					}
				},
				{
					"source": {
						"name": "test",
						"id": 2
					}
				},
				{
					"source": {
						"name": "test",
						"id": 3
					}
				}
				]
			'));
		$baseCrm = new BaseCrm('', $client);
		$baseCrm->getSources()->all(true);
	}

	public function testGet()
	{
		$client = \Mockery::mock(GuzzleClient::class);
		$client
			->shouldReceive('request')
			->once()
			->with('GET', sprintf('%s/%s/sources/123.json', Resource::ENDPOINT_SALES, Resource::PREFIX), $this->getQuery())
			->andReturn($this->getResponse(200, '
				{
					"source": {
						"name": "test",
						"id": 123
					}
				}
			'));
		$baseCrm = new BaseCrm('', $client);
		$sources = $baseCrm->getSources();
		/** @var Source $source */
		$source = $sources->get(123);

		$this->assertInstanceOf(Source::class, $source);
		$this->assertEquals(123, $source->id);
		$this->assertEquals('test', $source->name);

		$client
			->shouldReceive('request')
			->once()
			->with('PUT', sprintf('%s/%s/sources/123.json', Resource::ENDPOINT_SALES, Resource::PREFIX), $this->getQuery([
				'query' => [
					'source' => [
						'name' => 'modified',
					]
				],
			]))
			->andReturn($this->getResponse(200, '
				{
					"source": {
						"name": "modified",
						"id": 123
					}
				}
			'));

		$source->name = 'modified';
		$source->save([
			'name',
		]);
		$this->assertEquals('modified', $source->name);
	}

	public function testCreate()
	{
		$client = \Mockery::mock(GuzzleClient::class);
		$client
			->shouldReceive('request')
			->once()
			->with('POST', sprintf('%s/%s/sources.json', Resource::ENDPOINT_SALES, Resource::PREFIX), $this->getQuery([
				'query' => [
					'source' => [
						'name'                => 'test',
						'custom_field_values' => [],
					],
				],
			]))
			->andReturn($this->getResponse(200, '
				{
					"source": {
						"name": "test",
						"id": 405
					}
				}
			'));
		$baseCrm = new BaseCrm('', $client);
		$sources = $baseCrm->getSources();
		/** @var Source $source */
		$newSource       = (new DetachedSource);
		$newSource->name = 'test';
		$source          = $sources->create($newSource, [
			'name',
		]);
		$this->assertInstanceOf(Source::class, $source);
		$this->assertEquals(405, $source->id);
		$this->assertEquals('test', $source->name);
	}

	public function testDelete()
	{
		$client = \Mockery::mock(GuzzleClient::class);
		$client
			->shouldReceive('request')
			->once()
			->with('DELETE', sprintf('%s/%s/sources/123.json', Resource::ENDPOINT_SALES, Resource::PREFIX), $this->getQuery())
			->andReturn($this->getResponse(200, ''));
		$baseCrm = new BaseCrm('', $client);
		$sources = $baseCrm->getSources();
		$result  = $sources->delete(123);
		$this->assertTrue($result);
	}

	public function testCustomFields()
	{
		$client = \Mockery::mock(GuzzleClient::class);
		$client
			->shouldReceive('request')
			->once()
			->with('GET', sprintf('%s/%s/sources.json', Resource::ENDPOINT_SALES, Resource::PREFIX), $this->getQuery())
			->andReturn($this->getResponse(200, '
				[
					{
					"source": {
						"name": "test",
						"id": 1,
						"custom_fields": []
					}
				},
				{
					"source": {
						"name": "test",
						"id": 2,
						"custom_fields": {
							"custom1": {
								"id": null
							}
						}
					}
				},
				{
					"source": {
						"name": "test",
						"id": 3,
						"custom_fields": {
							"custom1": {
								"id": null
							},
							"custom2": {
								"id": 12345,
								"value": "some value"
							}
						}
					}
				}
				]
			'));
		$baseCrm = new BaseCrm('', $client);
		$sources = $baseCrm->getSources()->all();

		$i = 1;
		/** @var Source $source */
		foreach ($sources as $source)
		{
			$customFieldsCollection = $source->getCustomFields();
			$this->assertInstanceOf(CustomFieldsCollection::class, $customFieldsCollection);
			switch ($i)
			{
				case 1:
					$this->assertCount(0, $customFieldsCollection);
					$this->assertArrayNotHasKey('custom1', $customFieldsCollection);
					$this->assertEquals([], $customFieldsCollection->toArray());
					break;

				case 2:
					$this->assertCount(1, $customFieldsCollection);
					$this->assertArrayHasKey('custom1', $customFieldsCollection);
					$this->assertNull($customFieldsCollection['custom1']->getId());
					$this->assertArrayNotHasKey('custom2', $customFieldsCollection);
					$this->assertEquals(['custom1' => null], $customFieldsCollection->toArray());
					break;

				case 3:
					$this->assertCount(2, $customFieldsCollection);
					$this->assertArrayHasKey('custom1', $customFieldsCollection);
					$this->assertArrayHasKey('custom2', $customFieldsCollection);
					$this->assertEquals('custom2', $customFieldsCollection['custom2']->getName());
					$this->assertEquals('12345', $customFieldsCollection['custom2']->getId());
					$this->assertEquals('some value', $customFieldsCollection['custom2']->getValue());
					$this->assertEquals(['custom1' => null, 'custom2' => 'some value'], $customFieldsCollection->toArray());
					break;
			}
			++$i;
		}
	}

	public function testLazyLoadingWithCustomFields()
	{
		$client  = \Mockery::mock(GuzzleClient::class);
		$baseCrm = new BaseCrm('', $client);
		/** @var Source $source */
		$source = $baseCrm->getSources()->get(123);

		$client
			->shouldReceive('request')
			->once()
			->with('GET', sprintf('%s/%s/sources/123.json', Resource::ENDPOINT_SALES, Resource::PREFIX), $this->getQuery())
			->andReturn($this->getResponse(200, '
				{
					"source": {
						"name": "test",
						"id": 123,
						"custom_fields": {
							"custom1": {
								"id": null
							},
							"custom2": {
								"id": 12345,
								"value": "some value"
							}
						}
					}
				}
			'));

		$this->assertTrue($source->hasCustomField('custom1'));
		$this->assertTrue($source->hasCustomField('custom2'));
	}

	public function testSavingCustomFields()
	{
		$client  = \Mockery::mock(GuzzleClient::class);
		$baseCrm = new BaseCrm('', $client);
		/** @var Source $source */
		$source = $baseCrm->getSources()->get(123);

		$client
			->shouldReceive('request')
			->once()
			->with('GET', sprintf('%s/%s/sources/123.json', Resource::ENDPOINT_SALES, Resource::PREFIX), $this->getQuery())
			->andReturn($this->getResponse(200, '
				{
					"source": {
						"name": "test",
						"id": 123,
						"custom_fields": {
							"custom1": {
								"id": null
							},
							"custom2": {
								"id": 12345,
								"value": "some value"
							}
						}
					}
				}
			'));

		$source->setCustomField('custom1', 'new_value1');
		$source->setCustomField('custom2', 'new_value2');

		$client
			->shouldReceive('request')
			->once()
			->with('PUT', sprintf('%s/%s/sources/123.json', Resource::ENDPOINT_SALES, Resource::PREFIX), $this->getQuery([
				'query' => [
					'source' => [
						'name'                => 'test',
						'custom_field_values' => [
							'custom1' => 'new_value1',
							'custom2' => 'new_value2',
						],
					],
				],
			]))
			->andReturn($this->getResponse(200, '
				{
					"source": {
						"name": "test",
						"id": 123,
						"custom_fields": {
							"custom1": {
								"id": 123,
								"value": "new_value1"
							},
							"custom2": {
								"id": 456,
								"value": "new_value2"
							}
						}
					}
				}
			'));

		$source->save();
	}
}
