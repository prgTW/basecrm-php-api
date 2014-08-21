<?php

namespace prgTW\BaseCRM\Client;

interface ClientInterface
{
	/**
	 * @param string $method f.in. GET
	 * @param string $uri
	 * @param array  $options
	 *
	 * @return ResponseInterface
	 */
	public function request($method, $uri, array $options = []);
}
