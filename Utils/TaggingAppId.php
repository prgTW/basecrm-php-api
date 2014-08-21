<?php

namespace prgTW\BaseCRM\Utils;

/**
 * @method static TaggingAppId DEALS()
 * @method static TaggingAppId CONTACTS()
 * @method static TaggingAppId LEADS()
 */
class TaggingAppId extends NamedEnum
{
	const DEALS    = 1;
	const CONTACTS = 4;
	const LEADS    = 5;

	protected static $names = [
		self::DEALS    => 'Deal',
		self::CONTACTS => 'Contact',
		self::LEADS    => 'Lead',
	];
}
