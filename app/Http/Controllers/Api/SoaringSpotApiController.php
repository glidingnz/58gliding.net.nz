<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateDayAPIRequest;
use App\Http\Requests\API\UpdateDayAPIRequest;
use App\Repositories\DayRepository;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Http\Controllers\AppBaseController;
use Response;

/**
 * Class DayController
 * @package App\Http\Controllers\API
 */

class SoaringSpotAPIController extends AppBaseController
{
	
	protected $nonce;
	protected $date;
	protected $signature;

	public function __construct()
	{
	}

	protected function generate_signature($event_id)
	{
		$event = Event::find($event_id);

		if ($event)
		{
			$this->nonce = rtrim(base64_encode(openssl_random_pseudo_bytes(rand(12,30))), '=');
			$this->date = gmdate('Y-m-d\TH:i:s\Z');
			$this->signature = base64_encode(hash_hmac('sha256', $this->nonce . $this->date . $event->soaringspot_api_client_id , $event->soaringspot_api_secret, true));
			return true;
		}
		return false;
	}



	public function contests($event_id)
	{
		if ($this->generate_signature($event_id))
		{
			// 


			return $this->success($this->signature);
		}
		return $this->error();
	}

}
