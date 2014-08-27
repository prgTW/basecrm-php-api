<?php

namespace prgTW\BaseCRM\Tests\Resource;

use prgTW\BaseCRM\Resource\Page;
use prgTW\BaseCRM\Service\Detached\Lead;

class PageTest extends \PHPUnit_Framework_TestCase
{
	public function testPage()
	{
		$page = new Page([
			'items' => [
				new Lead(),
				new Lead(),
				new Lead(),
			]
		]);

		foreach ($page as $resource)
		{
			$this->assertInstanceOf(Lead::class, $resource);
		}
	}
}
