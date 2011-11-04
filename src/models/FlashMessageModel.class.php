<?php

class FlashMessageModel extends AgaviModel implements AgaviISingletonModel
{
	const FlashMessageNS = 'com.ecom.flashmsg';
	const FlashMessage_NORMAL = 0;
	const FlashMessage_WARN = 1;
	const FlashMessage_ERR = 2;
	private $severityClass = array(
		0 => 'flashmessage ui-state-highlight ui-corner-all',
		1 => 'flashmessage_warn ui-state-highlight ui-corner-all',
		2 => 'flashmessage_err ui-state-error ui-corner-all'
	);
	private $iconClass = array(
		0 => 'ui-icon-check',
		1 => 'ui-icon-notice',
		2 => 'ui-icon-alert'
	);

	/**
	 * The user associated with the model
	 * @var AgaviSecurityUser
	 */
	protected $user;

	/**
	 * Initializes the model
	 * @param AgaviContext $ctx
	 * @param array $parameters
	 */
	public function initialize(AgaviContext $ctx, array $parameters = array())
	{
		parent::initialize($ctx, $parameters);
		$this->user = $ctx->getUser();
	}

	/**
	 * @deprecated
	 * @return array
	 */
	public function getMessage()
	{
		return $this->getMessages();
	}

	/**
	 * Return the messages
	 * @return array messages
	 */
	public function getMessages()
	{
		if ($this->user->hasAttribute('message', self::FlashMessageNS)) {
			return $this->user->removeAttribute('message', self::FlashMessageNS);
		}
		else {
			return null;
		}
	}

	/**
	 * @deprecated
	 * @param string $message
	 * @param int $severity
	 */
	public function setMessage($message, $severity = self::FlashMessage_NORMAL)
	{
		$this->addMessage($message, $severity);
	}

	/**
	 * Add a new message to the container
	 * @param string $message
	 * @param int $severity
	 */
	public function addMessage($message, $severity = self::FlashMessage_NORMAL)
	{
		$msg_array = $this->user->getAttribute('message', self::FlashMessageNS);
		$msg_array[md5($message)] = array(
			'message' => $message,
			'class' => $this->severityClass[$severity],
			'icon' => $this->iconClass[$severity],
			'severity' => $severity
		);
		$this->user->setAttribute('message', $msg_array, self::FlashMessageNS);
	}
}

?>
