<?php

namespace prgTW\BaseCRM\Tests;

use prgTW\BaseCRM\Service\Rest\Source;
use prgTW\BaseCRM\Service\Rest\Sources;

class SourcesTest extends AbstractTest
{
	public function testAccountGet()
	{
		$sources = $this->client->sources;
		$this->assertInstanceOf(Sources::class, $sources);
		foreach ($sources as $source)
		{
			$this->assertInstanceOf(Source::class, $source);
		}
	}
}
