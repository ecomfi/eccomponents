<?php

class EcStringValidator extends EcValidatorBase
{

	protected function validate()
	{
		$utf8 = $this->getParameter('utf8', true);

		$originalValue =& $this->getData($this->getArgument());



		if ($originalValue == '') {
			if ($this->getParameter('required')) {
				$this->throwError('required_error');
				return false;
			}
			$originalValue = null;
		}
		else {

			if(!is_scalar($originalValue)) {
				// non scalar values would cause notices
				$this->throwError();
				return false;
			}
			if($this->getParameter('trim', false)) {
				if($utf8) {
					$pattern = '/^\p{Z}*(?P<trimmed>.*?)\p{Z}*$/Du';
				} else {
					$pattern = '/^\s*(?P<trimmed>.*?)\s*$/D';
				}
				if(preg_match($pattern, $originalValue, $matches)) {
					$originalValue = $matches['trimmed'];
				}
			}

			$value = $originalValue;

			if($utf8) {
				$value = utf8_decode($value);
			}

			if($this->hasParameter('min') and strlen($value) < $this->getParameter('min')) {
				$this->throwError('min');
				return false;
			}

			if($this->hasParameter('max') and strlen($value) > $this->getParameter('max')) {
				$this->throwError('max');
				return false;
			}

			if($this->hasParameter('modify_func')) {
				$functions = $this->getParameter('modify_func');
				$functions = is_array($functions) ? $functions : array($functions);
				foreach($functions as $f) {
					$originalValue = $f($originalValue);
				}
			}

			if($this->getParameter('strip_non_iso885915')) { //oletetaan utf-8
				$originalValue = iconv('UTF-8', 'ISO-8859-15//TRANSLIT//IGNORE', $originalValue);
				$originalValue = iconv('ISO-8859-15', 'UTF-8//TRANSLIT//IGNORE', $originalValue);
			}
		}

		$this->export($originalValue);

		return true;
	}
}

?>