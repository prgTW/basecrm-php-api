<?php

namespace prgTW\BaseCRM\Transport;

use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Message\ResponseInterface;
use prgTW\BaseCRM\Client\ClientInterface;
use prgTW\BaseCRM\Client\GuzzleClient;
use prgTW\BaseCRM\Exception\RepresentationErrorException;
use prgTW\BaseCRM\Utils\Convert;

class Transport
{
	const TOKEN_FUTUERSIMPLE_NAME = 'X-Futuresimple-Token';
	const TOKEN_PIPEJUMP_NAME     = 'X-Pipejump-Auth';

	/** @var ClientInterface */
	private $client;

	/** @var string */
	private $token;

	/** @var ResponseInterface */
	protected $lastResponse;

	/**
	 * @param string          $token
	 * @param ClientInterface $client
	 */
	public function __construct($token = '', ClientInterface $client = null)
	{
		$this->client       = null !== $client ? $client : new GuzzleClient;
		$this->token        = $token;
		$this->lastResponse = null;
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
		$options  = $this->getOptions($options);
		$response = $this->client->request('GET', $this->getUri($uri), $options);
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
		$options  = $this->getOptions($options);
		$response = $this->client->request('POST', $this->getUri($uri), $options);
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
		$options  = $this->getOptions($options);
		$response = $this->client->request('PUT', $this->getUri($uri), $options);
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
		$options  = $this->getOptions($options);
		$response = $this->client->request('DELETE', $this->getUri($uri), $options);
		$decoded  = $this->processResponse($response, $key);

		return $decoded;
	}

	/**
	 * @param ResponseInterface $response
	 * @param string            $key
	 *
	 * @throws RepresentationErrorException on 422 response
	 * @throws ClientException on 4xx errors
	 * @throws ServerException on 5xx errors
	 * @throws BadResponseException when key is not found in response data
	 * @return array|bool
	 */
	private function processResponse(ResponseInterface $response, $key = null)
	{
		$status = $response->getStatusCode();
		if (204 == $status)
		{
			return true;
		}

		$decoded = $response->json();
		$decoded = Convert::objectToArray($decoded);

		if (422 == $status)
		{
			//@codeCoverageIgnoreStart
			throw new RepresentationErrorException('', $this->client->getLastRequest(), $response);
			//@codeCoverageIgnoreEnd
		}

		if (200 <= $status && 300 > $status)
		{
			$this->lastResponse = $response;

			if (null === $key)
			{
				return $decoded;
			}

			if (false === array_key_exists($key, $decoded))
			{
				//@codeCoverageIgnoreStart
				$message = sprintf('Key "%s" not found in data', $key);
				throw new BadResponseException($message, $this->client->getLastRequest(), $response);
				//@codeCoverageIgnoreEnd
			}

			return $decoded[$key];
		}

		//@codeCoverageIgnoreStart
		throw BadResponseException::create($this->client->getLastRequest(), $response);
		//@codeCoverageIgnoreEnd
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

	/**
	 * @param array $options
	 *
	 * @return array
	 */
	private function getOptions(array $options)
	{
		$options = array_merge_recursive([
			'headers' => [
				self::TOKEN_PIPEJUMP_NAME     => $this->token,
				self::TOKEN_FUTUERSIMPLE_NAME => $this->token,
			],
		], $options);

		return $options;
	}
}
