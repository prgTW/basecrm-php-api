<?php

namespace prgTW\BaseCRM\Tests\Service;

use prgTW\BaseCRM\BaseCrm;
use prgTW\BaseCRM\Client\GuzzleClient;
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
			->twice()
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
			$source->save();
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
		$source->save();
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
						'name' => 'test',
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
		$newSource = (new DetachedSource);
		$newSource->name = 'test';
		$source = $sources->create($newSource);
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
		$this->assertNull($result);
	}
}
