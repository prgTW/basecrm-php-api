<?php

namespace prgTW\BaseCRM\Exception;

use GuzzleHttp\Exception\ClientException;
use prgTW\BaseCRM\Error\Error;

class RepresentationErrorException extends ClientException
{
	/**
	 * @param string $field
	 *
	 * @return array|Error[]
	 */
	public function getErrors($field = null)
	{
		$decoded = $this->getResponse()->json();

		$errors = [];
		foreach ($decoded['errors'] as $resourceName => $errorsData)
		{
			foreach ($errorsData as $errorData)
			{
				$errorData = $errorData['error'];
				if (null !== $field && $errorData['field'] !== $field)
				{
					continue;
				}
				$errors[] = new Error($errorData['code'], $errorData['field'], $errorData['description']);
			}
		}

		return $errors;
	}

	/**
	 * @param string $field
	 *
	 * @return bool
	 */
	public function hasErrors($field = null)
	{
		$errors = $this->getErrors($field);

		return 0 !== count($errors);
	}
}
