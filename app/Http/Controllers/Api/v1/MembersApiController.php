<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;

use App\Classes\MemberUtilities;
use App\Http\Requests;
use App\Http\Controllers\Api\ApiController;
use App\Models\Member;
use App\Models\MemberChangeLog;
use App\Models\Org;
use DB;
use DateTime;
use Excel;
use Gate;
use Auth;
use URL;
use Mailgun;

class MembersApiController extends ApiController
{

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		if ($member = Member::find($id))
		{
			// check if the current user can edit this user. If so, they can see all details.
			if (Gate::denies('edit-member', $member))
			{
				$this->_filter_view_results($member);
			}

			return $this->success($member);
		}
		return $this->not_found();
	}


	public function update(Request $request, $id)
	{

		// check member exists
		if (!$member = Member::find($id))
		{
			return $this->not_found();
		}

		// check we have permission to edit the member
		if (Gate::denies('edit-member', $member)) {
			return $this->denied();
		}

		// filter to only the items we're allowed to edit
		//$this->_filter_edit_results($member);

		//$form = $request->all();
		//unset($form['id']);

		// only allow updating of the correct items depending on your user access.
		if (Gate::allows('edit-self', $member) || Gate::allows('edit-member', $member))
		{
			$form['first_name'] = $request->get('first_name');
			$form['middle_name'] = $request->get('middle_name');
			$form['last_name'] = $request->get('last_name');
			$form['email'] = $request->get('email');
			$form['gender'] = $request->get('gender');
			$form['address_1'] = $request->get('address_1');
			$form['address_2'] = $request->get('address_2');
			$form['city'] = $request->get('city');
			$form['country'] = $request->get('country');
			$form['zip_post'] = $request->get('zip_post');
			$form['home_phone'] = $request->get('home_phone');
			$form['city'] = $request->get('city');
			$form['mobile_phone'] = $request->get('mobile_phone');
			$form['business_phone'] = $request->get('business_phone');
			$form['coach'] = $request->get('coach');
			$form['contest_pilot'] = $request->get('contest_pilot');
			$form['privacy'] = $request->get('privacy');
		}

		// Official awards
		if (Gate::allows('edit-awards'))
		{
			$form['observer_number'] = $request->get('observer_number');
			$form['awards'] = $request->get('awards');
			$form['qgp_number'] = $request->get('qgp_number');
			$form['date_of_qgp'] = $request->get('date_of_qgp');
			$form['silver_certificate_number'] = $request->get('silver_certificate_number');
			$form['silver_duration'] = $request->get('silver_duration');
			$form['silver_distance'] = $request->get('silver_distance');
			$form['silver_height'] = $request->get('silver_height');
			$form['gold_badge_number'] = $request->get('gold_badge_number');
			$form['gold_distance'] = $request->get('gold_distance');
			$form['gold_height'] = $request->get('gold_height');
			$form['diamond_distance_number'] = $request->get('diamond_distance_number');
			$form['diamond_height_number'] = $request->get('diamond_height_number');
			$form['diamond_goal_number'] = $request->get('diamond_goal_number');
			$form['all_3_diamonds_number'] = $request->get('all_3_diamonds_number');
			$form['flight_1000km_number'] = $request->get('flight_1000km_number');
			$form['flight_1250km_number'] = $request->get('flight_1250km_number');
			$form['flight_1500km_number'] = $request->get('flight_1500km_number');
		}

		// club administrators of the users club only

		// get the user we're trying to edit's club
		$org = Org::where('gnz_code', $member->club)->first();

		if (($org && Gate::allows('club-admin', $org)) || gate::allows('admin')) 
		{
			$form['date_of_birth'] = $request->get('date_of_birth');
			$form['instructor'] = $request->get('instructor');
			$form['instructor_rating'] = $request->get('instructor_rating');
			$form['aero_tow'] = $request->get('aero_tow');
			$form['instructor'] = $request->get('instructor');
			$form['aero_tow'] = $request->get('aero_tow');
			$form['winch_rating'] = $request->get('winch_rating');
			$form['self_launch'] = $request->get('self_launch');
			$form['insttrain'] = $request->get('insttrain');
			$form['tow_pilot'] = $request->get('tow_pilot');
			$form['instructor_trainer'] = $request->get('instructor_trainer');
			$form['tow_pilot_instructor'] = $request->get('tow_pilot_instructor');
			$form['aero_instructor'] = $request->get('aero_instructor');
			$form['advanced_aero_instructor'] = $request->get('advanced_aero_instructor');
			$form['auto_tow'] = $request->get('auto_tow');
		}

		if (Gate::allows('admin'))
		{
			$form['nzga_number'] = $request->get('nzga_number');
			$form['comments'] = $request->get('comments');
			$form['pending_approval'] = $request->get('pending_approval');
			$form['access_level'] = $request->get('access_level');
			$form['non_member'] = $request->get('non_member');
			$form['membership_type'] = $request->get('membership_type');
			$form['club'] = $request->get('club');
			$form['date_joined'] = $request->get('date_joined');
			$form['gnz_family_member_number'] = $request->get('gnz_family_member_number');
			$form['resigned'] = $request->get('resigned');
			$form['previous_clubs'] = $request->get('previous_clubs');
			$form['resigned_comment'] = $request->get('resigned_comment');
		}

		// log any changes
		$this->_check_for_changes($form, $member);

		$member->fill($form);
		if ($member->save())
		{
			return $this->success();
		}
		return $this->error();
		
	}


	protected function _check_for_changes($form, $member)
	{
		foreach ($form AS $key=>$form_item)
		{
			if ($form_item!=$member->$key)
			{
				$this->log_member_change($member, 'Update', $key, $member->$key, $form[$key]);
			}
		}
	}


	public function log_member_change($member, $action, $field, $oldval, $newval)
	{
		$user = Auth::user();
		$now = DB::raw('NOW()');

		$changeLog = new MemberChangeLog;
		$changeLog->description = "[gliding.net.nz] User  {$user->first_name} {$user->last_name} {$user->email} (ID: {$user->id}) modified {$field} of Member: {$member->first_name} {$member->last_name} (GNZ {$member->nzga_number}).";
		$changeLog->action = $action;
		$changeLog->field = $field;
		$changeLog->oldval = $oldval;
		$changeLog->newval = $newval;
		$changeLog->created = $now;
		$changeLog->id_member = $member->id;
		$changeLog->id_user = $user->id;

		$changeLog->save();
	}

	/**
	 * Similar to index, except will send email to the list.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function email(Request $request)
	{
		if (Gate::denies('gnz-member')) {
			return $this->denied();
		}

		if (!$request->input('from')) return $this->error('From email is required');
		if (!$request->input('message')) return $this->error('Message is required');
		if (!$request->input('subject')) return $this->error('Subject is required');

		$query = $this->_get_filtered_members($request);

		// only get the few fields we need
		$query->select('email', 'first_name', 'last_name');

		$data['text']=$request->input('message');

		if ($members = $query->get())
		{

			Mailgun::send(['html' => 'emails.markdown-email', 'text' => 'emails.rawtext-email'], $data, function ($message) use ($request, $members) {
				
				foreach ($members AS $member)
				{
					$message->to($member->email, $member->first_name, ['lastname'=>$member->last_name]);
				}

				$message->from($request->input('from'), 'Gliding New Zealand Mailing List');
				$message->subject($request->input('subject'));
			});

			return $this->success();
		}
		return $this->error();
	}


	/**
	 * Generates a query for the filtered list of members
	 *
	 * @return Query
	 */
	protected function _get_filtered_members($request)
	{

		$query = Member::query();
		$query->orderBy('last_name');

		if ($request->input('search'))
		{
			$s = '%' . $request->input('search') .'%';
			$query->where(function($query) use ($s) {
				$query->where('first_name','like',$s);
				$query->orWhere('last_name','like',$s);
				$query->orWhere('nzga_number','like',$s);
				$query->orWhere('club','like',$s);
			});
		}

		if (!$request->input('resigned'))
		{
			$query->where(function($query) {
				$query->where('membership_type','!=','Resigned');
			});
		}

		if ($request->input('org') && $request->input('org')!='null')
		{
			// special cases for soaring centres

			switch ($request->input('org'))
			{
				case 'MSC':
					$query->whereIn('club',['AKL','AAV','PKO','TPO','TGA','TRK']);
					break;
				case 'GSC':
					$query->whereIn('club',['GWR','WLN']);
					break;
				case 'OSC':
					$query->whereIn('club',['MLB','WLN', 'NLN', 'CTY', 'COT', 'SCY', 'OGC', 'CLV']);
					break;
				default:
					// for most normal clubs
					$query->where('club','=',$request->input('org'));
					break;
			}

		}

		switch ($request->input('type'))
		{
			case 'glider-pilot':
				$query->where(function($query) {
					$query->where('membership_type','=','Flying');
				});
				break;
			case 'instructors':
				$query->where(function($query) {
					$query->where('instructor','=','1');
				});
				break;
			case 'tow-pilots':
				$query->where(function($query) {
					$query->where('tow_pilot','=','1');
				});
				break;
			case 'qgp':
				$query->where(function($query) {
					$query->where('qgp_number','>','0');
				});
				break;
			case 'coaches':
				$query->where(function($query) {
					$query->where('coach','=','1');
				});
				break;
			case 'contest_pilots':
				$query->where(function($query) {
					$query->where('contest_pilot','=','1');
				});
				break;
			case 'students':
			case 'non-qgp':
				$query->where(function($query) {
					$query->where('qgp_number','=','NULL');
					$query->orWhere('qgp_number','=','0');
				});
				$query->where('tow_pilot','!=','1');
				$query->where('membership_type','!=','Mag Only');
				break;
			case 'oo':
				$query->where('observer_number','!=','NULL');
				$query->where('observer_number','!=','');
				$query->where('observer_number','!=','0');
				break;
			case 'youth':
				// check which month we are in to decide which year we need
				if (date("n")<=10)
				{
					// less or equal to October, so use this year
					$theyear=date("Y");
				}
				else
				{
					// after october, so use next year
					$theyear=date("Y") + 1;
				}
				$thirty_first_of_oct =  $theyear . '-10-31';
				$query->whereRaw('DATE_FORMAT(FROM_DAYS(DATEDIFF("'.$thirty_first_of_oct.'", date_of_birth)),"%Y")+0 BETWEEN 1 AND 26');
				$query->where('membership_type','!=','Mag Only');
				break;
		}

		return $query;
	}


	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request)
	{
		if (Gate::denies('gnz-member')) {
			return $this->denied();
		}

		$query = $this->_get_filtered_members($request);

		// if specified, generate a CSV or excel file instead
		if ($request->input('format')=='csv' || $request->input('format')=='xls')
		{
			$members = $query->get();
			$this->_filter_view_results($members);

			// generate a random key to identify and download the file
			$random_filename = randomkeys(10);

			// export the file in the specified format
			$file_details = Excel::create($random_filename, function($excel) use($members) {
				$excel->sheet('Sheet 1', function($sheet) use($members) {
					$sheet->fromArray($members);
				});
			})->store($request->input('format'), false, true);

			// create the download URL to return
			$return_details['url'] = URL::to('/members/download/' . $random_filename . '.' . $request->input('format'));

			return $this->success($return_details);

		}

		// otherwise paginate the results
		if ($members = $query->paginate($request->input('per-page', 50)))
		{
			$this->_filter_view_results($members);
			return $this->success($members, TRUE);
		}
		return $this->error(); 
	}

	// filter out any columns that shouldn't be displayed unless you have permission
	protected function _filter_view_results(&$members)
	{
		if (Gate::allows('membership-view')) {
			return true;
		}

		if (Gate::denies('club-admin')) {
			$members->makeHidden("date_of_birth");
			$members->makeHidden("access_level");
			$members->makeHidden("comments");
			$members->makeHidden("address_1");
			$members->makeHidden("address_2");

			// Remove any sensitve data if privacy flag set on this user
			if($members instanceof \Traversable) {
				$iteratable_members = $members;
			} else {
				$iteratable_members[] = $members;
			}

			foreach ($iteratable_members as $member) {
				if ($member->privacy) {
					$member->makeHidden("email");
					$member->makeHidden("city");
					$member->makeHidden("country");
					$member->makeHidden("home_phone");
					$member->makeHidden("mobile_phone");
					$member->makeHidden("business_phone");
				}
			}
		}
	}

	// filter out any columns that shouldn't be edited unless you have permission
	protected function _filter_edit_results(&$member)
	{
		if (Gate::denies('club-admin') && Gate::denies('view-membership')) {
			$member->makeHidden("date_of_birth");
			$member->makeHidden("access_level");
			$member->makeHidden("address_1");
			$member->makeHidden("address_2");
			$member->makeHidden("comments");
			$member->makeHidden("modified");
			$member->makeHidden("created");
			$member->makeHidden("zip_post");
		}
	}

	public function anonymous_member_dates()
	{
		$query = Member::query();
		$query->orderBy('id');

		// only get the few fields we need
		$query->select(DB::raw('if (resigned IS NOT NULL, 1, 0) AS current_member'));
		$query->addSelect('id', 'club', 'date_joined', 'date_of_qgp', 'resigned');

		$results = $query->get();

		return $this->success($results);
	}

	/**
	 * Optionally pass a date. If not provided use today.
	 */
	public function address_changes($limit_date=null)
	{
		$MemberUtilities = new MemberUtilities();
		if ($users = $MemberUtilities->get_address_changes($limit_date)) {
			return $this->success($users);
		}
		return $this->error();
	}

}