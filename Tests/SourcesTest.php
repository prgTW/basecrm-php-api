<?php

namespace prgTW\BaseCRM\Tests;

use prgTW\BaseCRM\Service\Source;
use prgTW\BaseCRM\Service\Sources;

class SourcesTest extends AbstractTest
{
	public function testSourcesGet()
	{
		$sources = $this->baseCrm->sources;
		$this->assertInstanceOf(Sources::class, $sources);
		foreach ($sources as $source)
		{
			$this->assertInstanceOf(Source::class, $source);
		}
	}
}
