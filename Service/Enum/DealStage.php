<?php

namespace prgTW\BaseCRM\Service\Enum;

use MyCLabs\Enum\Enum;

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
class DealStage extends Enum
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
}
