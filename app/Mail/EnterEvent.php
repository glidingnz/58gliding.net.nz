<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EnterEvent extends Mailable
{
	use Queueable, SerializesModels;

	// the event we are entering
	public $event;

	// the entry that has been created
	public $entry;

	public $host;

	/**
	 * Create a new message instance.
	 *
	 * @return void
	 */
	public function __construct($event, $entry)
	{
		$this->event = $event;
		$this->entry = $entry;
		$this->host = request()->getSchemeAndHttpHost();
	}

	/**
	 * Build the message.
	 *
	 * @return $this
	 */
	public function build()
	{
		return $this->subject('Event Entry Form')->view('emails.enter-event');
	}
}
