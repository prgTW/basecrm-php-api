<?php

namespace prgTW\BaseCRM\Tests\Resource;

use prgTW\BaseCRM\Resource\Page;
use prgTW\BaseCRM\Service\Detached\Lead;
use prgTW\BaseCRM\Tests\AbstractTest;

class PageTest extends AbstractTest
{
	public function testPage()
	{
		$page = new Page([
			'items' => [
				new Lead(),
				new Lead(),
				new Lead(),
			]
		], 'lead');

		foreach ($page as $resource)
		{
			$this->assertInstanceOf(Lead::class, $resource);
		}
	}
}
