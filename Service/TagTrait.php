<?php

namespace prgTW\BaseCRM\Service;

/**
 * @property array data
 */
trait TagTrait
{
	/** {@inheritdoc} */
	protected function preDehydrate()
	{
		return array_intersect_key($this->data, array_flip([
			'app_id',
			'taggable_id',
			'taggable_type',
			'tag_list',
		]));
	}
}
