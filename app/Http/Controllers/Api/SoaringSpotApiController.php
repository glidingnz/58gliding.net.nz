<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateDayAPIRequest;
use App\Http\Requests\API\UpdateDayAPIRequest;
use App\Repositories\DayRepository;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Http\Controllers\AppBaseController;
use Response;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp;

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

	protected function generate_signature($event)
	{
		if ($event)
		{
			$this->nonce = rtrim(base64_encode(openssl_random_pseudo_bytes(rand(12,30))), '=');
			$this->date = gmdate('Y-m-d\TH:i:s\Z');
			$this->signature = base64_encode(hash_hmac('sha256', $this->nonce . $this->date . $event->soaringspot_api_client_id, $event->soaringspot_api_secret, true));
			return true;
		}
		return false;
	}


	public function tasks($event_id)
	{
		$tasks = [];

		if ($event = Event::where('id', $event_id)->first()->makeVisible(['soaringspot_api_secret'])) {
			if ($this->generate_signature($event))
			{
				$client = new GuzzleHttp\Client();
				$auth_header = 'http://api.soaringspot.com/v1/hmac/v1 ClientID="'.$event->soaringspot_api_client_id.'", Signature="'.$this->signature.'", Nonce="'.$this->nonce.'", Created="'.$this->date.'"';
				$result = $client->request('GET', 'http://api.soaringspot.com/v1/', [
					'json' => [],
					'headers' => [
						'Authorization' => $auth_header
					]
				]);

				$data = $result->getBody()->getContents();
				print_r($data);

				return $this->success($data);
			}

			
		}
		return $this->error();
	}


}
