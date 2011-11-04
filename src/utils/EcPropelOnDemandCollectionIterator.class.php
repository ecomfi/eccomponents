<?php

/**
 * Description of EcPropelOnDemanCollectionIterator
 *
 * @author nnarhinen
 */
class EcPropelOnDemandCollectionIterator extends EcPropelResultIterator
{
	/**
	 *
	 * @var PropelOnDemandCollection
	 */
	protected $collection;
	
	/**
	 *
	 * @var Iterator
	 */
	protected $internalIterator;
	
	public function __construct($result, $idMethod='getId', $fkAccessors=array(), $fkCriterias=array())
	{
		parent::__construct(null, $idMethod, $fkAccessors, $fkCriterias);
		$this->collection = $result;
		$this->internalIterator = $this->collection->getIterator();
	}
	
	public function current()
	{
		$obj = $this->internalIterator->current();
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
	
	public function count()
	{
		return $this->collection->count();
	}
	
	public function key()
	{
		if($this->idMethod) {
			$idMethod = $this->idMethod;
			return $this->internalIterator->current()->$idMethod();
		}

		return $this->internalIterator->key();
	}

	public function next()
	{
		$this->internalIterator->next();
	}

	public function rewind()
	{
		$this->internalIterator->rewind();
	}

	public function valid()
	{
		return $this->internalIterator->valid();
	}
	
	public function toJson()
	{
		if ($this->idMethod) {
			$str = "{";
		}
		else {
			$str = "[";
		}
		foreach ($this as $id => $item) {
			if ($this->idMethod) {
				$str .= "\"$id\":";
			}
			foreach ($item as $property => $value) {
				if ($value instanceof DateTime) {
					$item[$property] = $value->format('c');
				}
			}
			$str .= json_encode($item) . ',';
		}
		$str = rtrim($str, ',');
		if ($this->idMethod) {
			$str .= '}';
		}
		else {
			$str .= ']';
		}
		return $str;
	}
}

?>
