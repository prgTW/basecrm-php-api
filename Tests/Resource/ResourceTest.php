<?php

namespace prgTW\BaseCRM\Tests\Resource;

use prgTW\BaseCRM\BaseCrm;
use prgTW\BaseCRM\Client\Client;
use prgTW\BaseCRM\Service\Account;
use prgTW\BaseCRM\Service\Leads;
use prgTW\BaseCRM\Service\Sources;
use prgTW\BaseCRM\Tests\AbstractTest;

class ResourceTest extends AbstractTest
{
	public function testSubResources()
	{
		$baseCrm      = new BaseCrm('', \Mockery::mock(Client::class));
		$subResources = $baseCrm->getSubResources();
		$this->assertEquals(3, count($subResources));
		$this->assertEquals(['account', 'sources', 'leads'], array_keys($subResources));
		$this->assertInstanceOf(Account::class, $subResources['account']);
		$this->assertInstanceOf(Sources::class, $subResources['sources']);
		$this->assertInstanceOf(Leads::class, $subResources['leads']);
	}
}
