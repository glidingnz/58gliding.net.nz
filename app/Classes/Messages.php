<?php
namespace App\Classes;

class Messages
{
	//protected $sessionHash;
	//protected $messages_table;

	protected $messages = Array();
	protected $error_count = 0;


	public function success($text)
	{
		$message['type']='success';
		$message['text']=$text;
		$this->messages[] = $message;
	}

	public function error($text)
	{
		$this->error_count++;
		$message['type']='error';
		$message['text']=$text;
		$this->messages[] = $message;
	}

	public function warning($text)
	{
		$message['type']='warning';
		$message['text']=$text;
		$this->messages[] = $message;
	}
	public function note($text)
	{
		$message['type']='note';
		$message['text']=$text;
		$this->messages[] = $message;
	}

	public function error_count()
	{
		return $this->error_count;
	}

	public function fetch()
	{
		return $this->messages;
	}

	/*
	public function store()
	{
		foreach ($this->messages AS $key => $message)
		{
			$this->messages_table->insert_message($message['type'], $message['text']);
			unset($this->messages[$key]);
		}
		return true;
	}

	public function load_messages()
	{
		if ($messages = $this->messages_table->get_session_messages())
		{
			foreach ($messages AS $message)
			{
				// add to the internal array of messages
				$this->messages[] = Array('type' => $message['messageType'], 'text' => $message['messageText']);
			}
		}
		return $this->messages;
	}
	*/

}
