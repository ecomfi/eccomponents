<?php

class EcIterableContainer implements Iterator, ArrayAccess, Countable
{
	/**
	 * First index in data array. Used for rewinding. Set explicitly if not 0.
	 *
	 * @var        int
	 */
	protected $first = 0;

	/**
	 * Current position
	 *
	 * @var        int
	 */
	protected $position = 0;

	/**
	 * Data container.
	 *
	 * @var        array
	 */
	protected $data = array();


	/**
	 * ArrayAccess
	 */
	public function offsetSet($offset, $value)
	{
        $this->data[$offset] = $value;
    }

    /**
	 * ArrayAccess
	 */
    public function offsetExists($offset)
    {
        return isset($this->data[$offset]);
    }

    /**
	 * ArrayAccess
	 */
    public function offsetUnset($offset)
    {
        unset($this->data[$offset]);
    }

    /**
	 * ArrayAccess
	 */
    public function offsetGet($offset)
    {
        return isset($this->data[$offset]) ? $this->data[$offset] : null;
    }

	/**
	 * Iterator::rewind()
	 */
	public function rewind()
	{
		$this->position = $this->first;
	}

	/**
	 * Iterator::current()
	 */
	public function current()
	{
		return $this->data[$this->position];
	}

	/**
	 * Iterator::key()
	 */
	public function key()
	{
		return $this->getPosition();
	}

	/**
	 * Iterator::next()
	 */
	public function next()
	{
		++$this->position;
	}

	/**
	 * Iterator::valid()
	 */
	public function valid()
	{
		return isset($this->data[$this->position]);
	}

	/**
	 * @param      int
	 * @return     void
	 * @throws     OutOfBoundsException
	 */
	public function seek($position)
	{
		if ($position < $this->first || $position >= ($this->first + $this->count())) {
			throw new OutOfBoundsException("Seeked position $position out of bounds");
		}

		$this->position = $position;
	}

	/**
	 * @return int
	 */
	public function count()
	{
		return count($this->data);
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
		return $this->getPosition() < ($this->first + $this->count() - 1);
	}

	/**
	 * @return boolean
	 */
	public function hasPrevious()
	{
		return $this->getPosition() > $this->first;
	}

	public function getNext()
	{
		if (!$this->hasNext()) {
			throw new OutOfBoundsException('Next element not available. Already at end array.');
		}

		//remember current pos
		$pos = $this->getPosition();

		//get next
		$this->next();
		$result = $this->current();

		//rewind to previous
		$this->seek($pos);

		return $result;
	}
}

?>