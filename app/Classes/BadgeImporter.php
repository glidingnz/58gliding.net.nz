<?php
namespace App\Classes;

use App\Models\Badge;
use App\Models\Member;
use App\Models\BadgeMember;

class BadgeImporter
{

	public $badges;

	public function import_badges()
	{
		$this->load_badges();

		$members = Member::all();
		foreach ($members as $member)
		{
			$this->import_badge($member);
		}
	}

	public function load_badges()
	{
		// get all badges
		$this->badges = Badge::all();

		// set the keys for the badges, so we can look them up easily
		foreach ($this->badges AS $key=>$badge)
		{
			$this->badges[$badge->slug] = $badge;
			unset($this->badges[$key]);
		}
	}


	public function import_badge($member)
	{

		if ($member->qgp_number>0)
		{
			$badgeMember = BadgeMember::firstOrNew(['member_id' => $member->id, 'badge_id' => $this->badges['qgp']->id]);
			$badgeMember->badge_id = $this->badges['qgp']->id;
			$badgeMember->awarded_date = $member->date_of_qgp;
			$badgeMember->member_id = $member->id;
			$badgeMember->save();
		}

		if ($member->silver_certificate_number>0)
		{
			$badgeMember = BadgeMember::firstOrNew(['member_id' => $member->id, 'badge_id' => $this->badges['fai-silver-badge']->id]);
			$badgeMember->badge_id = $this->badges['fai-silver-badge']->id;
			$badgeMember->member_id = $member->id;
			$badgeMember->badge_number = $member->silver_certificate_number;
			$badgeMember->save();
		}

		if ($member->silver_duration==1)
		{
			$badgeMember = BadgeMember::firstOrNew(['member_id' => $member->id, 'badge_id' => $this->badges['fai-5hr']->id]);
			$badgeMember->badge_id = $this->badges['fai-5hr']->id;
			$badgeMember->member_id = $member->id;
			$badgeMember->save();
		}

		if ($member->silver_distance==1)
		{
			$badgeMember = BadgeMember::firstOrNew(['member_id' => $member->id, 'badge_id' => $this->badges['fai-silver-50km']->id]);
			$badgeMember->badge_id = $this->badges['fai-silver-50km']->id;
			$badgeMember->member_id = $member->id;
			$badgeMember->save();
		}

		if ($member->silver_height==1)
		{
			$badgeMember = BadgeMember::firstOrNew(['member_id' => $member->id, 'badge_id' => $this->badges['fai-silver-1000m']->id]);
			$badgeMember->badge_id = $this->badges['fai-silver-1000m']->id;
			$badgeMember->member_id = $member->id;
			$badgeMember->save();
		}

		if ($member->gold_badge_number>0)
		{
			$badgeMember = BadgeMember::firstOrNew(['member_id' => $member->id, 'badge_id' => $this->badges['fai-gold-badge']->id]);
			$badgeMember->badge_id = $this->badges['fai-gold-badge']->id;
			$badgeMember->member_id = $member->id;
			$badgeMember->badge_number = $member->gold_badge_number;
			$badgeMember->save();
		}
		
		if ($member->gold_distance==1)
		{
			$badgeMember = BadgeMember::firstOrNew(['member_id' => $member->id, 'badge_id' => $this->badges['fai-gold-300']->id]);
			$badgeMember->badge_id = $this->badges['fai-gold-300']->id;
			$badgeMember->member_id = $member->id;
			$badgeMember->save();
		}
		
		if ($member->gold_height==1)
		{
			$badgeMember = BadgeMember::firstOrNew(['member_id' => $member->id, 'badge_id' => $this->badges['fai-gold-3000m']->id]);
			$badgeMember->badge_id = $this->badges['fai-gold-3000m']->id;
			$badgeMember->member_id = $member->id;
			$badgeMember->save();
		}

		if ($member->diamond_distance_number>0)
		{
			$badgeMember = BadgeMember::firstOrNew(['member_id' => $member->id, 'badge_id' => $this->badges['fai-diamond-500km']->id]);
			$badgeMember->badge_id = $this->badges['fai-diamond-500km']->id;
			$badgeMember->member_id = $member->id;
			$badgeMember->badge_number = $member->diamond_distance_number;
			$badgeMember->save();
		}

		if ($member->diamond_height_number>0)
		{
			$badgeMember = BadgeMember::firstOrNew(['member_id' => $member->id, 'badge_id' => $this->badges['fai-diamond-5000m']->id]);
			$badgeMember->badge_id = $this->badges['fai-diamond-5000m']->id;
			$badgeMember->member_id = $member->id;
			$badgeMember->badge_number = $member->diamond_height_number;
			$badgeMember->save();
		}

		if ($member->diamond_goal_number>0)
		{
			$badgeMember = BadgeMember::firstOrNew(['member_id' => $member->id, 'badge_id' => $this->badges['fai-diamond-300km']->id]);
			$badgeMember->badge_id = $this->badges['fai-diamond-300km']->id;
			$badgeMember->member_id = $member->id;
			$badgeMember->badge_number = $member->diamond_goal_number;
			$badgeMember->save();
		}

		if ($member->all_3_diamonds_number>0)
		{
			$badgeMember = BadgeMember::firstOrNew(['member_id' => $member->id, 'badge_id' => $this->badges['fai-3-diamond']->id]);
			$badgeMember->badge_id = $this->badges['fai-3-diamond']->id;
			$badgeMember->member_id = $member->id;
			$badgeMember->badge_number = $member->all_3_diamonds_number;
			$badgeMember->save();
		}
		
		if ($member->flight_1000km_number>0)
		{
			$badgeMember = BadgeMember::firstOrNew(['member_id' => $member->id, 'badge_id' => $this->badges['fai-1000km']->id]);
			$badgeMember->badge_id = $this->badges['fai-1000km']->id;
			$badgeMember->member_id = $member->id;
			$badgeMember->badge_number = $member->flight_1000km_number;
			$badgeMember->save();
		}
		
		if ($member->flight_1250km_number>0)
		{
			$badgeMember = BadgeMember::firstOrNew(['member_id' => $member->id, 'badge_id' => $this->badges['fai-1250km']->id]);
			$badgeMember->badge_id = $this->badges['fai-1250km']->id;
			$badgeMember->member_id = $member->id;
			$badgeMember->badge_number = $member->flight_1000km_number;
			$badgeMember->save();
		}
		
		if ($member->flight_1500km_number>0)
		{
			$badgeMember = BadgeMember::firstOrNew(['member_id' => $member->id, 'badge_id' => $this->badges['fai-1500km']->id]);
			$badgeMember->badge_id = $this->badges['fai-1500km']->id;
			$badgeMember->member_id = $member->id;
			$badgeMember->badge_number = $member->flight_1500km_number;
			$badgeMember->save();
		}
	}
	
}