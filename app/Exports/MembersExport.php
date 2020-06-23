<?php

namespace App\Exports;

use App\Models\Member;
use App\Models\Rating;
use App\Models\RatingMember;
use App\Models\Badges;
use App\Models\BadgeMember;
use Schema;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use App\Classes\MemberUtilities;

class MembersExport implements FromCollection, WithHeadings, WithMapping
{
	use Exportable;

	protected $request;

	public function __construct($request)
	{
		$this->request = $request;
	}

	/**
	* @return \Illuminate\Support\Collection
	*/
	public function collection()
	{
		$memberUtilities = new MemberUtilities();
		$query = $memberUtilities->get_filtered_members($this->request);
		$members = $query->get();
		
		$memberUtilities->filter_view_results($members);

		return $members;
	}

	
	public function headings(): array
	{
		// specify column headings
		$columns[] = 'id';
		$columns[] = 'nzga_number';
		$columns[] = 'non_member';
		$columns[] = 'first_name';
		$columns[] = 'middle_name';
		$columns[] = 'last_name';
		$columns[] = 'email';
		$columns[] = 'modified';
		$columns[] = 'created';
		$columns[] = 'membership_type';
		$columns[] = 'club';
		$columns[] = 'date_joined';
		$columns[] = 'gender';
		$columns[] = 'address_1';
		$columns[] = 'address_2';
		$columns[] = 'city';
		$columns[] = 'country';
		$columns[] = 'zip_post';
		$columns[] = 'date_of_birth';
		$columns[] = 'home_phone';
		$columns[] = 'mobile_phone';
		$columns[] = 'business_phone';
		$columns[] = 'gnz_family_member_number';
		$columns[] = 'resigned';
		$columns[] = 'previous_clubs';
		$columns[] = 'resigned_comment';
		$columns[] = 'qgp_number';
		$columns[] = 'date_of_qgp';
		$columns[] = 'xcp_number';
		$columns[] = 'date_of_xcp';
		$columns[] = 'coach';
		$columns[] = 'contest_pilot';
		$columns[] = 'comments';
		// $columns[] = 'instructor';
		// $columns[] = 'instructor_rating';
		// $columns[] = 'aero_tow';
		// $columns[] = 'winch_rating';
		// $columns[] = 'self_launch';
		// $columns[] = 'insttrain';
		// $columns[] = 'observer_number';
		// $columns[] = 'tow_pilot';
		// $columns[] = 'awards';
		// $columns[] = 'silver_certificate_number';
		// $columns[] = 'silver_duration';
		// $columns[] = 'silver_distance';
		// $columns[] = 'silver_height';
		// $columns[] = 'gold_badge_number';
		// $columns[] = 'gold_distance';
		// $columns[] = 'gold_height';
		// $columns[] = 'diamond_distance_number';
		// $columns[] = 'diamond_height_number';
		// $columns[] = 'diamond_goal_number';
		// $columns[] = 'all_3_diamonds_number';
		// $columns[] = 'flight_1000km_number';
		// $columns[] = 'flight_1250km_number';
		// $columns[] = 'flight_1500km_number';
		// $columns[] = 'pending_approval';
		// $columns[] = 'instructor_trainer';
		// $columns[] = 'tow_pilot_instructor';
		// $columns[] = 'aero_instructor';
		// $columns[] = 'advanced_aero_instructor';
		// $columns[] = 'auto_tow';
		// $columns[] = 'privacy';
		
		return $columns;
	}

	/**
	* @var Invoice $member
	*/
	public function map($member): array
	{
		// items we're joining onto the main table
		$qgp = null;
		$qgp_date = null;
		$xcp = null;
		$xcp_date = null;

		// for each member, get their ratings
		$ratings = RatingMember::with(['rating'])->where('member_id', $member->id)->get();

		foreach ($ratings AS $rating)
		{
			switch ($rating->rating->name)
			{
				case 'QGP': 
					$qgp = $rating->number; 
					$qgp_date = $rating->awarded;
					break;
				case 'XCP':
					$xcp = $rating->number; 
					$xcp_date = $rating->awarded; 
					break;
			} 
		}

		// for each member, get their awards
		$badges = BadgeMember::with(['badge'])->where('member_id', $member->id)->get();

		foreach ($badges AS $badge)
		{
			switch ($badge->badge->name)
			{
				case 'QGP': 
					$qgp = $rating->number; 
					$qgp_date = $rating->awarded;
					break;
			}
		}

		return [
			$member->id,
			$member->nzga_number,
			$member->non_member,
			$member->first_name,
			$member->middle_name,
			$member->last_name,
			$member->email,
			$member->modified,
			$member->created,
			$member->membership_type,
			$member->club,
			$member->date_joined,
			$member->gender,
			$member->address_1,
			$member->address_2,
			$member->city,
			$member->country,
			$member->zip_post,
			$member->date_of_birth,
			$member->home_phone,
			$member->mobile_phone,
			$member->business_phone,
			$member->gnz_family_member_number,
			$member->resigned,
			$member->previous_clubs,
			$member->resigned_comment,
			$qgp,
			$qgp_date,
			$xcp,
			$xcp_date,
			$member->coach,
			$member->contest_pilot,
			$member->comments,
		];
	}
}
