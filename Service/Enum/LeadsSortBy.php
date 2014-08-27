<?php

namespace prgTW\BaseCRM\Service\Enum;

use MyCLabs\Enum\Enum;

/**
 * @method static LeadsSortBy ID_DESC()
 * @method static LeadsSortBy FIRST_NAME()
 * @method static LeadsSortBy LAST_NAME()
 * @method static LeadsSortBy ADDED_ON()
 */
class LeadsSortBy extends Enum
{
	const ID_DESC    = 'id_desc';
	const FIRST_NAME = 'first_name';
	const LAST_NAME  = 'last_name';
	const ADDED_ON   = 'added_on';
}
