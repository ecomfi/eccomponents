<?php

class EmailerModel extends AgaviModel
{
	/**
	 *
	 * @var Swift_Mailer
	 */
	private $swiftMailer;

	/**
	 *
	 * @var Swift_Message
	 */
	private $swiftMessage;

	public function initialize(AgaviContext $context, array $parameters = array())
	{
		parent::initialize($context, $parameters);
		require_once(dirname(__FILE__).'../../vendor/Swift/lib/swift_required.php');
		$this->swiftMailer = Swift_Mailer::newInstance(new Swift_MailTransport());
		$this->swiftMessage = Swift_Message::newInstance();
	}

	public function setTransport($swiftTransport)
	{
		$this->swiftMailer = Swift_Mailer::newInstance($swiftTransport);
	}

	public function setTo($email, $name = null)
	{
		$this->addTo($email, $name);
	}

	public function addTo($email, $name = null)
	{
		$this->swiftMessage->addTo($email, $name);
	}

	public function addCopy($email, $name = null)
	{
		$this->swiftMessage->addCC($email, $name);
	}

	public function setFrom($email, $name = null)
	{
		$from = $name ? array($email => $name) : array($email);
		$this->swiftMessage->setFrom($from);
	}

	public function setSubject($subject)
	{
		$this->swiftMessage->setSubject($subject);
	}

	public function setBody($message)
	{
		$this->swiftMessage->setBody($message);
	}

	public function send()
	{
		$this->swiftMailer->send($this->swiftMessage);
	}

	public function attachFile($file, $filename = null, $mimetype = null)
	{
		$attachment = null;
		if (!$filename && !$mimetype && file_exists($file)) {
			$attachment = Swift_Attachment::fromPath($file);
		}
		elseif ($filename && $mimetype) {
			$attachment = Swift_Attachment::newInstance()
				->setFilename($filename)
				->setContentType($mimetype)
				->setBody($file);
		}
		else {
			throw new EcEmailerException("Valittua liitettä ei osattu käsitellä");
		}
		$this->swiftMessage->attach($attachment);
	}
}

class EcEmailerException extends AgaviException { }

?>
