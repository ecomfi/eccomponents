<?php

class EcTimeValidator extends EcValidatorBase
{

	protected function validate()
	{
		$value = & $this->getData($this->getArgument());

		$value = trim($value);

		if ($value == '') {
			$value = null;
			if ($this->getParameter('required')) {
				$this->throwError('required_error');
				return false;
			}
			$this->export(null);
			return true;
		}

		$hours =  $minutes = null;

		$matches = array();
		if (preg_match("/^(\d{1,2})[\.|:]?(\d{1,2})?$/", trim($value), $matches)) {
			$hours = (int) ltrim($matches[1], '0');
			$minutes = isset($matches[2]) ? (int) ltrim($matches[2], '0') : 0;

			if (0 <= $hours && $hours <= 24 && 0 <= $minutes && $minutes <= 59) {
				$dt = new DateTime();
				$dt->setTime($hours, $minutes);
				$this->export($dt);
				return true;
			}

		}

		$this->throwError('error');
		return false;

	}
}

?>