<?php

namespace prgTW\BaseCRM;

use GuzzleHttp\Message\Response;
use prgTW\BaseCRM\Exception\RestException;
use prgTW\BaseCRM\Resource\Resource;
use prgTW\BaseCRM\Service\Rest\Account;
use prgTW\BaseCRM\Service\Rest\Sources;

/**
 * @property Account account
 * @property Sources sources
 */
class Client extends Resource
{
	const TOKEN_FUTUERSIMPLE_NAME = 'X-Futuresimple-Token';
	const TOKEN_PIPEJUMP_NAME     = 'X-Pipejump-Auth';

	/** @var string */
	private $token;

	/** @var \GuzzleHttp\Client */
	private $guzzle;

	/** @var Response */
	protected $lastResponse;

	/**
	 * @param string $token
	 */
	public function __construct($token)
	{
		$this->token  = $token;
		$this->client = $this;
		$this->setSubResources([
			Account::class,
			Sources::class,
		]);
	}

	/** {@inheritdoc} */
	protected function getEndpoint()
	{
		throw new \LogicException('Cannot call client directly');
	}

	/**
	 * @return array
	 */
	private function getAuthHeaders()
	{
		return array(
			self::TOKEN_PIPEJUMP_NAME     => $this->token,
			self::TOKEN_FUTUERSIMPLE_NAME => $this->token,
		);
	}

	/**
	 * @param string $method
	 * @param string $url
	 * @param array  $options
	 *
	 * @return \GuzzleHttp\Message\ResponseInterface
	 */
	protected function request($method, $url, $options = [])
	{
		if (null === $this->guzzle)
		{
			$this->guzzle = new \GuzzleHttp\Client();
		}

		$options  = array_merge_recursive([
			'headers' => $this->getAuthHeaders(),
		], $options);
		$request  = $this->guzzle->createRequest($method, $url, $options);
		$response = $this->guzzle->send($request);

		return $response;
	}

	/**
	 * @param string $uri
	 * @param string $key
	 * @param array  $options
	 *
	 * @return array|bool
	 */
	public function get($uri, $key = null, array $options = [])
	{
		$response = $this->request('GET', $this->getUri($uri), $options);
		$decoded  = $this->processResponse($response, $key);

		return $decoded;
	}

	/**
	 * @param string $uri
	 * @param string $key
	 * @param array  $options
	 *
	 * @return array|bool
	 */
	public function post($uri, $key = null, array $options = [])
	{
		$response = $this->request('POST', $this->getUri($uri), $options);
		$decoded  = $this->processResponse($response, $key);

		return $decoded;
	}

	/**
	 * @param string $uri
	 * @param string $key
	 * @param array  $options
	 *
	 * @return array|bool
	 */
	public function put($uri, $key = null, array $options = [])
	{
		$response = $this->request('PUT', $this->getUri($uri), $options);
		$decoded  = $this->processResponse($response, $key);

		return $decoded;
	}

	/**
	 * @param string $uri
	 * @param string $key
	 * @param array  $options
	 *
	 * @return array|bool
	 */
	public function delete($uri, $key = null, array $options = [])
	{
		$response = $this->request('DELETE', $this->getUri($uri), $options);
		$decoded  = $this->processResponse($response, $key);

		return $decoded;
	}

	/**
	 * @param Response $response
	 * @param string   $key
	 *
	 * @throws Exception\RestException
	 * @throws \InvalidArgumentException when key is not found in response data
	 * @return array|bool
	 */
	private function processResponse(Response $response, $key = null)
	{
		$status = $response->getStatusCode();
		if (204 === $status)
		{
			return true;
		}

		$decoded = $response->json();

		if (200 <= $status && 300 > $status)
		{
			$this->lastResponse = $response;

			if (null === $key)
			{
				return $decoded;
			}

			if (false === array_key_exists($key, $decoded))
			{
				throw new \InvalidArgumentException(sprintf('Key "%s" not found in data', $key));
			}

			return $decoded[$key];
		}

		throw new RestException($response->getBody()->getContents(), $status);
	}

	/**
	 * @param string $uri
	 *
	 * @return string
	 */
	protected function getUri($uri)
	{
		return $uri . '.json';
	}
}
