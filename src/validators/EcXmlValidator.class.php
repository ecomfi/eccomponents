<?php

/**
 *
 *
*/
class EcXmlValidator extends EcValidatorBase
{

	protected function validate()
	{
		$value = $this->getData($this->getArgument());

		if ($value == '') {
			if ($this->getParameter('required')) {
				$this->throwError('required_error');
				return false;
			}
			$value = null;
		}
		else {
			$dom = new DOMDocument('1.0', 'utf-8');
			$result = @$dom->loadXML('<domwrapper>' . $value . '</domwrapper>');

			if (!$result) {
				$this->throwError('invalid_error');
				return false;
			}
		}

		return true;
	}
}

?>