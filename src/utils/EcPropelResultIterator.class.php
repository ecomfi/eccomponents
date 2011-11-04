<?php

class EcPropelResultIterator extends EcIterableContainer
{
	protected $idMethod;

	protected $idLookUp = array();

	protected $fkAccessors = array();
	protected $fkCriterias = array();

	public function __construct($result, $idMethod='getId', $fkAccessors=array(), $fkCriterias=array())
	{
		$this->data = $result;
		$this->idMethod = $idMethod;
		$this->fkAccessors = $fkAccessors;
		$this->fkCriterias = $fkCriterias;
	}

	public function key()
	{
		if($this->idMethod) {
			$idMethod = $this->idMethod;
			$this->idLookUp[$this->data[$this->position]->$idMethod()] = $this->position;
			return $this->data[$this->position]->$idMethod();
		}

		return $this->position;
	}

	public function current()
	{
		/* @var $obj CoveringNote */
		$obj = $this->data[$this->position];
		if ($this->fkAccessors) {
			$result = $obj->toArray();
			foreach($this->fkAccessors as $name => $getter) {
				$fkObj = null;
				if (isset($this->fkCriterias[$name])) {
					$fkObj = $obj->$getter($this->fkCriterias[$name]);
				}
				else {
					$fkObj = $obj->$getter();
				}
				if ($fkObj === null) {
					$result[$name] = $fkObj;
				}
				elseif (is_numeric($fkObj)) { // Most likely a result of countForeignReference()
					$result[$name] = $fkObj;
				}
				else {
					$result[$name] = $fkObj->toArray();
				}
			}
			return $result;
		}
		else {
			return $obj->toArray();
		}
	}

	public function findByKey($key)
	{
		if($this->idMethod) {

			if (isset($this->idLookUp[$key])) { // id is already cached in the lookup table
				return $this->data[$this->idLookUp[$key]]->toArray();
			}

			//else: find the key
			$idMethod = $this->idMethod;
			for($i=$this->first; $i<$this->first+$this->count(); ++$i) {
				if ($this->data[$i]->$idMethod() == $key) {
					$this->idLookUp[$this->data[$i]->$idMethod()] = $i;
					return $this->data[$i]->toArray();
				}
			}
			return null;
		}
		else {
			try {
				$this->seek($key);
				return $this->current();
			}
			catch (OutOfBoundsException $e) {
				return null;
			}

		}
	}

	/**
	 * ArrayAccess
	 */
	public function offsetSet($offset, $value)
	{
		throw new BadMethodCallException('Not implemented');
    }

    /**
	 * ArrayAccess
	 */
    public function offsetExists($offset)
    {
		return $this->findByKey($offset) !== null;
    }

    /**
	 * ArrayAccess
	 */
    public function offsetUnset($offset)
    {
        throw new BadMethodCallException('Not implemented');
    }

    /**
	 * ArrayAccess
	 */
    public function offsetGet($offset)
    {
        return $this->findByKey($offset);
    }

		public function toJson()
		{
			$ret = array();
			foreach ($this as $key => $item) {
				foreach ($item as $property => $value) {
					if ($value instanceof DateTime) {
						$item[$property] = $value->format('c');
					}
				}
				$ret[$key] = $item;
			}
			return json_encode($ret);
		}
}

?>