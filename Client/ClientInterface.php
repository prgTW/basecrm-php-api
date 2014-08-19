<?php

namespace prgTW\BaseCRM\Client;

interface ClientInterface
{
	const TOKEN_FUTUERSIMPLE_NAME = 'X-Futuresimple-Token';
	const TOKEN_PIPEJUMP_NAME     = 'X-Pipejump-Auth';

	/**
	 * @param string $uri
	 * @param string $key
	 * @param array  $options
	 *
	 * @return array|bool
	 */
	public function delete($uri, $key = null, array $options = []);

	/**
	 * @param string $uri
	 * @param string $key
	 * @param array  $options
	 *
	 * @return array|bool
	 */
	public function get($uri, $key = null, array $options = []);

	/**
	 * @param string $uri
	 * @param string $key
	 * @param array  $options
	 *
	 * @return array|bool
	 */
	public function post($uri, $key = null, array $options = []);

	/**
	 * @param string $uri
	 * @param string $key
	 * @param array  $options
	 *
	 * @return array|bool
	 */
	public function put($uri, $key = null, array $options = []);
}
