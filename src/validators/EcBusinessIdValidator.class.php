<?php

class EcBusinessIdValidator extends EcValidatorBase
{

	protected function validate()
	{
		$value = $this->getData($this->getArgument());

		$value = trim($value);

		if ($value == '') {
			if ($this->getParameter('required')) {
				$this->throwError('required_error');
				return false;
			}
			$this->export(null);
			return true;
		}

		$companyId = str_replace('-', '', $value);

		if (strlen($companyId) != 8 || ( (int) $companyId) < 1) {
			$this->throwError();
			return false;
		}

		$id = substr($companyId, 0, strlen($companyId)-1);
		$check = (int) substr($companyId, -1);

		$factors = array(7, 9, 10, 5, 8, 4, 2);
		$numbers = str_split($id);
		$sum = 0;

		for($i=0; $i<7; ++$i) {
			$sum += $numbers[$i] * $factors[$i];
		}

		$check2 = ($sum%11 == 0) ? 0 : 11-($sum%11);

		if ($check !== $check2) {
			$this->throwError();
			return false;
		}

		$companyId = substr($companyId, 0, 7) . '-' . substr($companyId, 7);

		$this->export($companyId);
		return true;
	}

}
?>