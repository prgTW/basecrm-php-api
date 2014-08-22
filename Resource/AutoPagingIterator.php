<?php

namespace prgTW\BaseCRM\Resource;

class AutoPagingIterator extends PagingIterator
{
	/** {@inheritdoc} */
	public function count()
	{
		//@codeCoverageIgnoreStart
		throw new \BadMethodCallException('Cannot count partial collection');
		//@codeCoverageIgnoreEnd
	}

	/** {@inheritdoc} */
	protected function isLoadNecessary()
	{
		return parent::isLoadNecessary() || null === $this->key();
	}
}
