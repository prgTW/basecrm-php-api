<?php

namespace prgTW\BaseCRM\Client;

use GuzzleHttp\Message\ResponseInterface;
use prgTW\BaseCRM\Exception\RestException;

abstract class Client implements ClientInterface
{
	/** @var string */
	private $token;

	/** @var ResponseInterface */
	protected $lastResponse;

	/**
	 * @param string $token
	 */
	public function __construct($token = '')
	{
		$this->token        = $token;
		$this->lastResponse = null;
	}

	/** {@inheritdoc} */
	abstract protected function request($method, $uri, $options = []);

	/**
	 * @return array
	 */
	protected function getAuthHeaders()
	{
		return array(
			self::TOKEN_PIPEJUMP_NAME     => $this->token,
			self::TOKEN_FUTUERSIMPLE_NAME => $this->token,
		);
	}

	/** {@inheritdoc} */
	public function get($uri, $key = null, array $options = [])
	{
		$response = $this->request('GET', $this->getUri($uri), $options);
		$decoded  = $this->processResponse($response, $key);

		return $decoded;
	}

	/** {@inheritdoc} */
	public function post($uri, $key = null, array $options = [])
	{
		$response = $this->request('POST', $this->getUri($uri), $options);
		$decoded  = $this->processResponse($response, $key);

		return $decoded;
	}

	/** {@inheritdoc} */
	public function put($uri, $key = null, array $options = [])
	{
		$response = $this->request('PUT', $this->getUri($uri), $options);
		$decoded  = $this->processResponse($response, $key);

		return $decoded;
	}

	/** {@inheritdoc} */
	public function delete($uri, $key = null, array $options = [])
	{
		$response = $this->request('DELETE', $this->getUri($uri), $options);
		$decoded  = $this->processResponse($response, $key);

		return $decoded;
	}

	/**
	 * @param ResponseInterface $response
	 * @param string            $key
	 *
	 * @throws RestException
	 * @throws \InvalidArgumentException when key is not found in response data
	 * @return array|bool
	 */
	private function processResponse(ResponseInterface $response, $key = null)
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
