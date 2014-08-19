<?php

namespace prgTW\BaseCRM\Tests;

use prgTW\BaseCRM\BaseCrm;
use prgTW\BaseCRM\Client\Client;
use prgTW\BaseCRM\Resource\Resource;
use prgTW\BaseCRM\Service\Source;
use prgTW\BaseCRM\Service\Sources;

class SourcesTest extends AbstractTest
{
	public function testSourcesGet()
	{
		$client  = \Mockery::mock(Client::class)
			->shouldReceive('get')
			->twice()
			->with(sprintf('%s/%s/sources', Resource::ENDPOINT_SALES, Resource::PREFIX))
			->andReturn([
				['source' => ['id' => 1, 'name' => 'test']],
				['source' => ['id' => 2, 'name' => 'test']],
				['source' => ['id' => 3, 'name' => 'test']],
			])
			->getMock();
		$baseCrm = new BaseCrm('', $client);
		$sources = $baseCrm->sources;
		$this->assertInstanceOf(Sources::class, $sources);
		$this->assertEquals(3, count($sources));
		/** @var Source $source */
		foreach ($sources as $source)
		{
			$this->assertInstanceOf(Source::class, $source);
			$this->assertEquals('test', $source->getName());
		}
	}
}
