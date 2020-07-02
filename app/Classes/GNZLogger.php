<?php
namespace App\Classes;

use Auth;
use DB;
use App\Models\Member;
use App\Models\MemberChangeLog;

class GNZLogger
{

	public function log($member, $action, $field, $oldval=null, $newval=null)
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
}
