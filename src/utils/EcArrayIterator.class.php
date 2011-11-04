<?php

class EcArrayIterator extends ArrayIterator 
{
	protected $position;
	
	public function rewind()
	{
		parent::rewind();
		$this->position = 0;
	}

	public function next()
	{
		parent::next();
		++$this->position;
	}

	/**
	 * @param      int
	 * @return     void
	 * @throws     OutOfBoundsException
	 */
	public function seek($position)
	{
		parent::seek($position);
		$this->position = $position;
	}

	/**
	 * @return int
	 */
	public function getPosition()
	{
		return $this->position;
	}

	/**
	 * @return boolean
	 */
	public function hasNext()
	{
		return $this->getPosition() < $this->count()-1;
	}

	/**
	 * @return boolean
	 */
	public function hasPrevious()
	{
		return $this->getPosition() > 0;
	}
}


?>