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

				$contest_body = $result->getBody()->getContents();
				$contest_data = json_decode($contest_body, true);
				//print_r($contest_data);
				if (isset($contest_data['_embedded']['http://api.soaringspot.com/rel/contests'][0]['_embedded']['http://api.soaringspot.com/rel/classes'])) {
					$classes = $contest_data['_embedded']['http://api.soaringspot.com/rel/contests'][0]['_embedded']['http://api.soaringspot.com/rel/classes'];
					foreach ($classes AS $class) {
						//print_r($class);

						// get the tasks for the classes
						$tasks_results = $client->request('GET', 'http://api.soaringspot.com/v1/classes/'.$class['id'].'/tasks', [
							'json' => [],
							'headers' => [
								'Authorization' => $auth_header
							]
						]);
						$tasks_body = $tasks_results->getBody()->getContents();
						$tasks_data = json_decode($tasks_body, true);
						
						if (isset($tasks_data['_embedded']['http://api.soaringspot.com/rel/tasks'])) {
							$tasks_array = $tasks_data['_embedded']['http://api.soaringspot.com/rel/tasks'];
							foreach ($tasks_array AS $key=>$task) {
								if (isset($class['type'])) $task['class_type'] = $class['type'];
								if (isset($class['name'])) $task['class_name'] = $class['name'];
								$tasks[] = $task;
							}
						}
					}
				}

				return $this->success($tasks);
			}
		}
		return $this->error();
	}


	// load a task
	public function task($event_id, $task_id) {

		if ($event = Event::where('id', $event_id)->first()->makeVisible(['soaringspot_api_secret'])) {
			if ($this->generate_signature($event))
			{
				$client = new GuzzleHttp\Client();
				$auth_header = 'http://api.soaringspot.com/v1/hmac/v1 ClientID="'.$event->soaringspot_api_client_id.'", Signature="'.$this->signature.'", Nonce="'.$this->nonce.'", Created="'.$this->date.'"';
				$result = $client->request('GET', 'http://api.soaringspot.com/v1/tasks/' . $task_id . '/points', [
					'json' => [],
					'headers' => [
						'Authorization' => $auth_header
					]
				]);

				$task_body = $result->getBody()->getContents();
				$task_data = json_decode($task_body, true);
				//print_r($task_data);

				if (isset($task_data['_embedded']['http://api.soaringspot.com/rel/points'])) {
					$points = $task_data['_embedded']['http://api.soaringspot.com/rel/points'];

					// turn radians into decimals
					foreach ($points AS $key=>$point) {
						$points[$key]['lat'] = round(rad2deg($point['latitude']), 6);
						$points[$key]['lng'] = round(rad2deg($point['longitude']), 6);
					}

					return $this->success($points);
				}
			}
		}
		return $this->error();
	}
	

}
