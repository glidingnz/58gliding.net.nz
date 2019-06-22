<?php
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\Models\Member;

class GNZMemberSeeder extends Seeder
{

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		factory(Member::Class, 100)->create();
		DB::connection('gnz')->select("UPDATE gnz_member SET login_name=nzga_number");
	}
}