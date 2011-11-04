<?php

/**
 * A general purpose simple date validator
 *
 * @author Veikko Mäkinen
 * @author Niklas Närhinen
 *
 **/
class EcDateValidator extends EcValidatorBase
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

		$day =  $month = $year = null;

		$matches = array();
		if (preg_match("/^(\d{1,2})\.(\d{1,2})\.(\d{1,4})$/", trim($value), $matches)) {
			$day = (int) ltrim($matches[1], '0');
			$month = (int) ltrim($matches[2], '0');
			$year = (int) ltrim($matches[3], '0');
			if (0 <= $year && $year <= 38) {
				$year += 2000;
			}
			elseif (70 <= $year && $year <= 99) {
				$year += 1900;
			}
			elseif (!(1970 <= $year && $year <= 2038))
				$year = -1;

		}
		elseif (preg_match("/^(\d{1,2})\.(\d{1,2})\.?$/", trim($value), $matches)) {
			$day = (int) ltrim($matches[1], '0');
			$month = (int) ltrim($matches[2], '0');
			$today = getdate();
			$year = $today['year'];
		}
		elseif (preg_match("/^(\d{1,4})\-(\d{1,2})\-(\d{1,2})$/", trim($value), $matches)) {
			$day = (int) ltrim($matches[3], '0');
			$month = (int) ltrim($matches[2], '0');
			$year = (int) ltrim($matches[1], '0');
			if (0 <= $year && $year <= 38) {
				$year += 2000;
			}
			elseif (70 <= $year && $year <= 99) {
				$year += 1900;
			}
			elseif (!(1970 <= $year && $year <= 2038))
				$year = -1;
		}

		if (checkdate($month, $day, $year)) {
			if ($this->getParameter('export_unixts')) {
				$export = mktime(null, null, null, $month, $day, $year);
			}
			elseif ($this->hasParameter('export_format')) {
				$dt = new DateTime();
				$dt->setDate($year, $month, $day);
				$export = $dt->format($this->getParameter('export_format'));
			}
			else {
				$export = new DateTime();
				$export->setDate($year, $month, $day);
			}
			$this->export($export);
			return true;
		}

		$this->throwError();
		return false;
	}
}

?>