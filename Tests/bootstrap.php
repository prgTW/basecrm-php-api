<?php

call_user_func(function () {
	$autoloadFile = __DIR__ . '/../vendor/autoload.php';
	if (!is_file($autoloadFile))
	{
		throw new \LogicException(sprintf('Could not find %s. Did you run "composer install --dev"?', $autoloadFile));
	}

	$loader = require $autoloadFile;
	$loader->add('prgTW\\BaseCRM', __DIR__ . '/../');
});

$token = getenv('BASECRM_TOKEN');
if (empty($token))
{
	echo sprintf('BASECRM_TOKEN environmental variable must be set');
	exit;
}
