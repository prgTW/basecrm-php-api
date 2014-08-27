<?php

namespace prgTW\BaseCRM\Resource;

abstract class LazyLoadedResource extends Resource
{
	/** {@inheritdoc} */
	public function __get($name)
	{
		if (null === $this->data)
		{
			$this->lazyLoad();
		}

		return parent::__get($name);
	}

	abstract protected function lazyLoad();
}
