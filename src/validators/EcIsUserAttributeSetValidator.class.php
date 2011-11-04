<?php

/**
 *
 *
*/
class EcIsUserAttributeSetValidator extends EcValidatorBase
{

	protected function validate()
	{
		$attr = $this->getParameter('attribute');
		$ns = $this->getParameter('namespace');

		if (!$this->context->getUser()->hasAttribute($attr, $ns)) {
			$this->throwError();
			return false;
		}

		return true;
	}
}

?>