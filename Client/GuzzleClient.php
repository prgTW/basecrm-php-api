<?php

namespace prgTW\BaseCRM\Client;

use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Message\ResponseInterface;
use prgTW\BaseCRM\Exception;

class GuzzleClient extends Client
{
	/** @var \GuzzleHttp\Client */
	private $guzzle;

	/** @var ResponseInterface */
	protected $lastResponse;

	/**
	 * @param string $method
	 * @param string $uri
	 * @param array  $options
	 *
	 * @return ResponseInterface
	 */
	protected function request($method, $uri, $options = [])
	{
		if (null === $this->guzzle)
		{
			$this->guzzle = new GuzzleHttpClient();
		}

		$options  = array_merge_recursive([
			'headers' => $this->getAuthHeaders(),
		], $options);
		$request  = $this->guzzle->createRequest($method, $uri, $options);
		$response = $this->guzzle->send($request);

		return $response;
	}
}
