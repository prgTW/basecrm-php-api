<?php

namespace prgTW\BaseCRM;

use GuzzleHttp\Message\Response;
use prgTW\BaseCRM\Exception\RestException;
use prgTW\BaseCRM\Service\Resource;
use prgTW\BaseCRM\Service\Rest\Account;

/**
 * @property Account account
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
			'account',
		]);
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
	 * @param array  $options
	 *
	 * @return mixed
	 */
	public function get($uri, array $options = [])
	{
		$response = $this->request('GET', $this->getUri($uri), $options);
		$decoded  = $this->processResponse($response);

		return $decoded;
	}

	/**
	 * @param string $uri
	 * @param array  $options
	 *
	 * @return \GuzzleHttp\Message\ResponseInterface
	 */
	public function post($uri, array $options = [])
	{
		$response = $this->request('POST', $this->getUri($uri), $options);
		$decoded  = $this->processResponse($response);

		return $decoded;
	}

	/**
	 * @param string $uri
	 * @param array  $options
	 *
	 * @return \GuzzleHttp\Message\ResponseInterface
	 */
	public function put($uri, array $options = [])
	{
		$response = $this->request('PUT', $this->getUri($uri), $options);
		$decoded  = $this->processResponse($response);

		return $decoded;
	}

	/**
	 * @param string $uri
	 * @param array  $options
	 *
	 * @return \GuzzleHttp\Message\ResponseInterface
	 */
	public function delete($uri, array $options = [])
	{
		$response = $this->request('DELETE', $this->getUri($uri), $options);
		$decoded  = $this->processResponse($response);

		return $decoded;
	}

	/**
	 * @param Response $response
	 *
	 * @throws Exception\RestException
	 * @return mixed
	 */
	private function processResponse(Response $response)
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

			return $decoded;
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
