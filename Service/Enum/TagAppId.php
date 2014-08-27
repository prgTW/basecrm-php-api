<?php

namespace prgTW\BaseCRM\Service\Enum;

/**
 * @method static TagAppId DEALS()
 * @method static TagAppId CONTACTS()
 * @method static TagAppId LEADS()
 */
class TagAppId extends NamedEnum
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
