<?php

/**
 *
 *
*/
class EcIntegerListValidator extends EcValidatorBase
{

	protected function validate()
	{
		$export = array();
		$value = trim($this->getData($this->getArgument()));

		if ($value == '' || !preg_match('/\d/', $value)) {
			if ($this->getParameter('required')) {
				$this->throwError('required_error');
				return false;
			}
		}
		else {
			if (preg_match('/\d/', $value)) foreach (preg_split('/[^\d]+/', $value) as $id) {
				$temp = (int) $id;
				if(((string)$temp) !== ((string)$id) ) {
					$this->throwError();
					return false;
				}
				$export[] = (int) $id;
			}
		}

		$this->export($export);

		return true;
	}
}

?>