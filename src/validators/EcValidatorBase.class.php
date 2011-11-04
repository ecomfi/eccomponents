<?php

abstract class EcValidatorBase extends AgaviValidator
{

	protected function checkAllArgumentsSet($throwError = true)
	{
		$this->setParameter('required', $this->getParameter('required', true));
		return true;
	}

	protected function export($value, $name = null)
	{
		if ($name === null) {
			if ($this->getParameter('export_self')) {
				$args = $this->getFullArgumentNames();
				if ($args) {
					$name = $args[0];
				}
			}
			else {
				$name = $this->getParameter('export');
			}
		}

		parent::export($value, $name);
	}

}

?>