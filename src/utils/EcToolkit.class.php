<?php

class EcToolkit
{

	public static function createPassword($minLength=6, $maxLength=8)
	{
		$length = mt_rand($minLength, $maxLength);

		$result = '';
		do {
			$c = chr(mt_rand(48, 122));
			if(preg_match('/^[a-zA-Z0-9]$/', $c)) {
				$result .= $c;
			}
		} while(strlen($result) < $length);

		return $result;
	}

}


?>