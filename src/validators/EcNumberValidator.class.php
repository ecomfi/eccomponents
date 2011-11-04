<?php

/**
 *
 * Parameters:
 *   'type'       number type (int, integer or float)
 *   'type_error' error message if number has wrong type
 *   'min'        number must be at least this
 *   'min_error'  error message if number less then 'min'
 *   'max'        number must not be greater then this
 *   'max_error'  error message if number greater then 'max'
 *
*/
class EcNumberValidator extends EcValidatorBase
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
		else {
			switch(strtolower($this->getParameter('type'))) {
				case 'int':
				case 'integer':
					$temp = (int) $value;
					if(((string)$temp) !== ((string)$value) ) {
						$this->throwError('type_error');
						return false;
					}
					$value = (int) $value;

					break;
				case 'bigint':
					if(!ctype_digit($value) ) {
						$this->throwError('type_error');
						return false;
					}

					break;

				case 'float':
				default:
					$value = str_replace(',', '.', $value);
					if(!is_numeric($value)) {
						$this->throwError('type_error');
						return false;
					}
					$value = (float) $value;
					break;
			}

			if($this->hasParameter('min') and $value < $this->getParameter('min')) {
				$this->throwError('min_error');
				return false;
			}

			if($this->hasParameter('max') and $value > $this->getParameter('max')) {
				$this->throwError('max_error');
				return false;
			}
		}

		$this->export($value);

		return true;
	}
}

?>