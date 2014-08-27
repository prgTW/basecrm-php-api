<?php

namespace prgTW\BaseCRM\Service\Behavior;

use prgTW\BaseCRM\Service\Detached\Reminder;
use prgTW\BaseCRM\Service\Reminders;

/**
 * @method Reminders getReminders()
 */
trait RemindableTrait
{
	/**
	 * @param string    $content
	 * @param bool      $done
	 * @param \DateTime $date
	 *
	 * @return bool
	 */
	public function addReminder($content, $done = false, \DateTime $date = null)
	{
		$reminder          = new Reminder();
		$reminder->content = $content;
		$reminder->done    = $done;
		$reminder->remind  = false;
		if (null !== $date)
		{
			$reminder->remind = true;
			$reminder->date   = $date->format('Y/m/d');
			$reminder->hour   = $date->format('H');
		}

		/** @var Reminders $reminders */
		$reminders = $this->getReminders();
		$result    = $reminders->create($reminder, false);

		return $result;
	}
}
