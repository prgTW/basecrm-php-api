<?php

namespace prgTW\BaseCRM\Tests;

use GuzzleHttp\Message\Response;
use GuzzleHttp\Stream\Stream;
use prgTW\BaseCRM\Transport\Transport;

abstract class AbstractTest extends \PHPUnit_Framework_TestCase
{
	/** {@inheritdoc} */
	public function tearDown()
	{
		\Mockery::close();
	}

	/**
	 * @param array $query
	 *
	 * @return array
	 */
	protected function getQuery(array $query = [])
	{
		$query = array_merge_recursive([
			'headers' => [
				Transport::TOKEN_PIPEJUMP_NAME     => '',
				Transport::TOKEN_FUTUERSIMPLE_NAME => '',
			],
		], $query);

		return $query;
	}

	/**
	 * @param int    $status
	 * @param string $body
	 *
	 * @return Response
	 */
	protected function getResponse($status, $body)
	{
		$stream = fopen('php://temp', 'r+');
		fwrite($stream, $body, mb_strlen($body));
		rewind($stream);
		$body     = new Stream($stream);
		$response = new Response($status, [], $body);

		return $response;
	}
}
