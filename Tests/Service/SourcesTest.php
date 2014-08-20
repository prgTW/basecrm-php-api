<?php

namespace prgTW\BaseCRM\Tests\Service;

use prgTW\BaseCRM\BaseCrm;
use prgTW\BaseCRM\Client\Client;
use prgTW\BaseCRM\Resource\Resource;
use prgTW\BaseCRM\Service\Source;
use prgTW\BaseCRM\Tests\AbstractTest;

class SourcesTest extends AbstractTest
{
	public function testGet()
	{
		$client  = \Mockery::mock(Client::class)
			->shouldReceive('get')
			->twice()
			->with(sprintf('%s/%s/sources', Resource::ENDPOINT_SALES, Resource::PREFIX))
			->andReturn([
				[
					'source' => [
						'name' => 'test',
						'id'   => 371
					]
				],
				[
					'source' => [
						'name' => 'test',
						'id'   => 372
					]
				],
				[
					'source' => [
						'name' => 'test',
						'id'   => 373
					]
				]
			])
			->getMock();
		$baseCrm = new BaseCrm('', $client);
		$sources = $baseCrm->getSources();
		$this->assertCount(3, $sources);
		/** @var Source $source */
		foreach ($sources as $source)
		{
			$this->assertInstanceOf(Source::class, $source);
			$this->assertEquals('test', $source->getName());

			$client
				->shouldReceive('put')
				->once()
				->with(sprintf('%s/%s/sources/%d', Resource::ENDPOINT_SALES, Resource::PREFIX, $source->getId()), 'source', [
					'query' => [
						'source' => [
							'name' => 'test',
						],
					],
				])
				->andReturn([
					'source' => [
						'id'   => $source->getId(),
						'name' => 'test',
					],
				]);
			$source->save();
		}
	}
}
