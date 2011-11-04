<?php

/**
 *
 *
*/
class EcStringArrayValidator extends EcValidatorBase
{

	protected function validate()
	{
		$export = array();
		$value = trim($this->getData($this->getArgument()));

		if ($value === null || strlen($value) == 0) {
			if ($this->getParameter('required')) {
				$this->throwError('required');
				return false;
			}
		}
		else {
			$separator = $this->hasParameter('separator') ? $this->getParameter('separator') : ';';
			$export = explode($separator, $value);
		}

		$this->export($export);

		return true;
	}
}

?>