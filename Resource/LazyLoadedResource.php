<?php

namespace prgTW\BaseCRM\Resource;

abstract class LazyLoadedResource extends Resource
{
	/** {@inheritdoc} */
	public function __get($name)
	{
		$this->lazyLoadIfNecessary();

		return parent::__get($name);
	}

	protected function lazyLoadIfNecessary()
	{
		if (null === $this->data)
		{
			$this->lazyLoad();
		}
	}

	abstract protected function lazyLoad();
}
