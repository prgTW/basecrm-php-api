<?php

namespace prgTW\BaseCRM\Tests\Error;

use GuzzleHttp\Message\Request;
use GuzzleHttp\Message\Response;
use GuzzleHttp\Stream\Stream;
use prgTW\BaseCRM\Exception\RepresentationErrorException;

class RepresentationErrorExceptionTest extends \PHPUnit_Framework_TestCase
{
	public function testRepresentation()
	{
		$stream   = Stream::factory('{
			"errors": {
				"contact": [
					{
						"error": {
							"code": "E0001",
							"field": "last_name",
							"description": "Please provide contact\'s last name."
						}
					}
				]
			}
		}');
		$response = new Response(422, [], $stream);

		$exception = new RepresentationErrorException('', new Request('POST', ''), $response);

		$this->assertTrue($exception->hasErrors());
		$this->assertTrue($exception->hasErrors('last_name'));
		$this->assertFalse($exception->hasErrors('non_existing'));

		$this->assertCount(1, $exception->getErrors());
		$this->assertCount(1, $exception->getErrors('last_name'));
		$this->assertCount(0, $exception->getErrors('non_existing'));

		$this->assertEquals('E0001', $exception->getErrors()[0]->getCode());
		$this->assertEquals('last_name', $exception->getErrors()[0]->getField());
		$this->assertEquals('Please provide contact\'s last name.', $exception->getErrors()[0]->getDescription());
	}
}
