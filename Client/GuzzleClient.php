<?php

namespace prgTW\BaseCRM\Client;

use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Message\RequestInterface;

/**
 * @codeCoverageIgnore
 */
class GuzzleClient implements ClientInterface
{
	/** @var \GuzzleHttp\Client */
	private $guzzle;

	/** @var RequestInterface */
	protected $lastRequest;

	/**
	 * @param string $method
	 * @param string $uri
	 * @param array  $options
	 *
	 * @return ResponseInterface
	 */
	public function request($method, $uri, array $options = [])
	{
		$this->lazyLoadGuzzle();

		$this->lastRequest = $this->guzzle->createRequest($method, $uri, $options);
		$response          = $this->guzzle->send($this->lastRequest);

		return $response;
	}

	/**
	 * @return RequestInterface
	 */
	public function getLastRequest()
	{
		return $this->lastRequest;
	}

	protected function lazyLoadGuzzle()
	{
		if (null === $this->guzzle)
		{
			$this->guzzle = new GuzzleHttpClient([
				'exceptions' => false,
				'defaults' = [
					'config' => [
						'curl' => [
							CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1_2,
						],
					],
				],
			]);
		}
	}
}
