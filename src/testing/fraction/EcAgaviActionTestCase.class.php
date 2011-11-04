<?php
/**
 * A helper class to inject params to action instance
 *
 * @author Veikko Mäkinen
 * 
 **/ 
class EcAgaviActionTestCase extends AgaviActionTestCase
{
	protected $actionInits = array();

	/**
	 *
	 * @param string $function
	 * @param mixed $params
	 * @param boolean $callWithoutParams Call without params
	 * @param boolean $paramsAsList Params as list instead of an array as one parameter
	 */
	protected function addActionInit($function, $params, $callWithoutParams=false, $paramsAsList=false)
	{
		$this->actionInits[$function] = array(
			'params' => $params,
			'without_params' => $callWithoutParams,			
		);

		if($paramsAsList){
			throw new Exception('Not implemented');
		}
	}

	protected function createActionInstance()
	{
		$action = parent::createActionInstance();
		if (count($this->actionInits)) {
			foreach ($this->actionInits as $function => $params) {
				if ($params['without_params']) {
					$action->$function();
				}
				else {
					$action->$function($params['params']);
				}
			}
		}
		return $action;
	}

}

?>
