<?php

namespace Email;

class Email_Driver_Noop extends \Email_Driver
{
	/**
	 * Just print the mail to the log file
	 *
	 * @return	bool	success boolean.
	 */
	protected function _send()
	{
		$message = $this->build_message();
		\Log::debug("Sending mail using noop driver");
		\Log::debug('To: '. $this->to);
		\Log::debug('Subject: '. $this->subject);
		\Log::debug('Body: '. $message['body']);		
		\Log::debug('Header: '. $message['header']);
		\Log::debug('From: '. $this->config['from']['email']);		
		return true;
	}

}
