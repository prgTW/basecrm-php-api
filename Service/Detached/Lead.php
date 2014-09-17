<?php

namespace prgTW\BaseCRM\Service\Detached;

use prgTW\BaseCRM\Resource\DetachedResource;
use prgTW\BaseCRM\Service\Behavior\CustomFieldsTrait;

class Lead extends DetachedResource
{
	use CustomFieldsTrait;
}
