<?php

namespace prgTW\BaseCRM\Client;

use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Message\ResponseInterface;

/**
 * @codeCoverageIgnore
 */
class GuzzleClient extends GuzzleHttpClient implements ClientInterface
{
	/** @var \GuzzleHttp\Client */
	private $guzzle;

	/**
	 * @param string $method
	 * @param string $uri
	 * @param array  $options
	 *
	 * @return ResponseInterface
	 */
	public function request($method, $uri, array $options = [])
	{
		if (null === $this->guzzle)
		{
			$this->guzzle = new GuzzleHttpClient();
		}

		$request  = $this->guzzle->createRequest($method, $uri, $options);
		$response = $this->guzzle->send($request);

		return $response;
	}
}
