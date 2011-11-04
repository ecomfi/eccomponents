<?php
class EcAgaviWebRequest extends AgaviWebRequest 
{

	public function initialize(AgaviContext $context, array $parameters = array()) 
	{
		parent::initialize($context, $parameters);
	
		if (isset($_SERVER['CONTENT_TYPE'])
				&& preg_match('#^application/json(;[^;]+)*?$#', $_SERVER['CONTENT_TYPE'])) {
			
			$file = $this->getMethod() == 'write' ? 'post_file' : 'put_file';
			$rd = $this->getRequestData();
			$file = $rd->getFile($file);
			$jsonStr = $file->getContents();
			$jsonArr = json_decode($jsonStr, true);
			if (is_array($jsonArr)) {
				$rd->setParameters($jsonArr);
			}
		}

	}
}
