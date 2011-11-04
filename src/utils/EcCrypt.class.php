<?php

class EcCrypt
{

	protected $cipher;

	protected $mode;

	/**
	 *
	 * @param string $cipher http://www.php.net/manual/en/mcrypt.ciphers.php
	 * @param string $mode http://www.php.net/manual/en/mcrypt.constants.php
	 */
	public function __construct($cipher=MCRYPT_RIJNDAEL_256, $mode = MCRYPT_MODE_CBC)
	{
		$this->cipher = $cipher;
		$this->mode = $mode;
	}

	/**
	 * @param string $data
	 * @param string $key
	 * @return string Hexadecimal presentation of the crypted data and the used initialization vector
	 */
	public function encrypt($data, $key)
	{
		$iv = $this->createIV();

		$crypted = mcrypt_encrypt($this->cipher, $key, $data, $this->mode, $iv);

		$result = bin2hex($crypted);

		if ($this->mode != MCRYPT_MODE_ECB) {
			$result .= bin2hex($iv);
		}

		return $result;
	}

	/**
	 * @param string $data
	 * @param string $key
	 * @return string Decrypted data
	 */
	public function decrypt($data, $key)
	{
		if ($this->mode != MCRYPT_MODE_ECB) {
			$crypted = pack('H*', substr($data, 0, -2*$this->getIVSize()));
			$iv = pack('H*', substr($data, -2*$this->getIVSize()));
		}
		else {
			$crypted = pack('H*', $data);
			$iv = $this->createIV();
		}

		return trim(mcrypt_decrypt($this->cipher, $key, $crypted, $this->mode, $iv));
	}

	/**
	 * Creates an initialization vector to be used with the encryption
	 *
	 * @return string
	 */
	protected function createIV()
	{
		if ($this->mode != MCRYPT_MODE_ECB) {
			srand();
			return mcrypt_create_iv($this->getIVSize(), MCRYPT_RAND);
		}
		else {
			return str_repeat(' ', $this->getIVSize());
		}
	}

	/**
	 * Returns the size of the initialization vector for selected cipher mode
	 *
	 * @return int
	 */
	protected function getIVSize()
	{
		return mcrypt_get_iv_size($this->cipher, $this->mode);
	}

}


?>