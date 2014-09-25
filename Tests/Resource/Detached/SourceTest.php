<?php

namespace prgTW\BaseCRM\Tests\Detached;

use prgTW\BaseCRM\Service\Detached\Source;

class SourceTest extends \PHPUnit_Framework_TestCase
{
	public function testCustomFields()
	{
		$source     = new Source();
		$source->id = 1;
		$source->setCustomField('custom1', 'value1');
		$dehydrated = $source->dehydrate();

		$this->assertEquals([
			'id'                  => 1,
			'custom_field_values' => [
				'custom1' => 'value1',
			],
		], $dehydrated);
	}
}
