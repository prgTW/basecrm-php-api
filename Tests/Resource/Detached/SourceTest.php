<?php

namespace prgTW\BaseCRM\Tests\Detached;

use prgTW\BaseCRM\Resource\CustomField;
use prgTW\BaseCRM\Resource\CustomFieldsCollection;
use prgTW\BaseCRM\Service\Detached\Source;

class SourceTest extends \PHPUnit_Framework_TestCase
{
	public function testCustomFields()
	{
		$source     = new Source();
		$source->id = 1;
		$source->setCustomField('custom1', 'value1');
		$customFields = $source->getCustomFields()->toArray();

		$this->assertEquals(['custom1' => 'value1'], $customFields);
		$this->assertInstanceOf(CustomFieldsCollection::class, $source->getCustomFields());
		$this->assertInstanceOf(CustomField::class, $source->getCustomField('custom1'));
		$this->assertNull($source->getCustomField('custom1')->getId());
		$this->assertEquals('custom1', $source->getCustomField('custom1')->getName());
		$this->assertEquals('value1', $source->getCustomField('custom1')->getValue());
		$this->assertEquals(['id' => null, 'value' => 'value1'], $source->getCustomField('custom1')->getData());
	}

	/**
	 * @expectedException \PHPUnit_Framework_Error_Warning
	 */
	public function testNonExistentField()
	{
		$source = new Source();
		$source->non_existent_field;
	}

	public function testMagicMethods()
	{
		$source     = new Source();
		$source->id = 1;

		$this->assertEquals(1, $source->id);
		$this->assertEmpty(false, $source->id);
		$this->assertFalse(empty($source->id));
		$this->assertTrue(isset($source->id));
	}
}
