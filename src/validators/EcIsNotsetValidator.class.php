<?php

class EcIsNotsetValidator extends AgaviValidator
{

	protected function checkAllArgumentsSet($throwError = true)
	{
		if($this->getParameter('required', true)) {
			return true;
		} else {
			return parent::checkAllArgumentsSet($throwError);
		}
	}

	protected function validate()
	{
		$params = $this->validationParameters->getAll($this->getParameter('source'));

		foreach($this->getArguments() as $argument) {
			if($this->curBase->hasValueByChildPath($argument, $params)) {
				$this->throwError();
				return false;
			}
		}

		return true;
	}
}

?>