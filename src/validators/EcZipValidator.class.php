<?php

/**
 *
 *
*/
class EcZipValidator extends EcValidatorBase
{

	protected function validate()
	{

		$value = trim($this->getData($this->getArgument()));

		if ($value == '') {

			if ($this->getParameter('required')) {
				$this->throwError('required_error');
				return false;
			}
			$value = null;
		}
		elseif (!preg_match('/^\d{5}$/', $value)) {
			$this->throwError('type_error');
			return false;
		}

		return true;
	}
}

?>