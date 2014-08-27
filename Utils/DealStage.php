<?php

namespace prgTW\BaseCRM\Utils;

/**
 * @method static DealStage INCOMING()
 * @method static DealStage QUALIFIED()
 * @method static DealStage QUOTE()
 * @method static DealStage CUSTOM1()
 * @method static DealStage CUSTOM2()
 * @method static DealStage CUSTOM3()
 * @method static DealStage CLOSURE()
 * @method static DealStage WON()
 * @method static DealStage LOST()
 * @method static DealStage UNQUALIFIED()
 */
class DealStage extends NamedEnum
{
	const INCOMING    = 'incoming';
	const QUALIFIED   = 'qualified';
	const QUOTE       = 'quote';
	const CUSTOM1     = 'custom1';
	const CUSTOM2     = 'custom2';
	const CUSTOM3     = 'custom3';
	const CLOSURE     = 'closure';
	const WON         = 'won';
	const LOST        = 'lost';
	const UNQUALIFIED = 'unqualified';

	protected static $names = [
		self::INCOMING    => 'incoming',
		self::QUALIFIED   => 'qualified',
		self::QUOTE       => 'quote',
		self::CUSTOM1     => 'custom1',
		self::CUSTOM2     => 'custom2',
		self::CUSTOM3     => 'custom3',
		self::CLOSURE     => 'closure',
		self::WON         => 'won',
		self::LOST        => 'lost',
		self::UNQUALIFIED => 'unqualified',
	];
}
