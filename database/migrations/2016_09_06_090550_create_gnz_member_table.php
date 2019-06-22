<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGnzMemberTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

		$env = getenv('APP_ENV');
		if ($env=='production') {
			return true;
		}

		Schema::create('gnz_member', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('nzga_number')->nullable()->unique('nzga_id');
			$table->string('login_name', 64)->nullable()->unique('login_name');
			$table->string('password', 40);
			$table->string('salt', 40);
			$table->enum('access_level', array('MEMBER_ADMIN','CLUB_ADMIN','AWARDS_OFFICER','MAGAZINE_OFFICER','NORMAL','NATOPS_OFFICER'));
			$table->boolean('non_member')->default(0);
			$table->string('first_name', 64);
			$table->string('middle_name', 64)->nullable();
			$table->string('last_name', 64);
			$table->string('email', 64);
			$table->timestamp('modified')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->timestamp('created')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->string('membership_type', 64)->nullable();
			$table->string('club', 3)->nullable();
			$table->date('date_joined')->nullable();
			$table->enum('gender', array('M','F'))->nullable();
			$table->string('address_1', 50)->nullable();
			$table->string('address_2', 50)->nullable();
			$table->string('city', 50)->nullable();
			$table->string('country', 32)->nullable();
			$table->string('zip_post', 32)->nullable();
			$table->date('date_of_birth')->nullable();
			$table->string('home_phone', 32)->nullable();
			$table->string('mobile_phone', 32)->nullable();
			$table->string('business_phone', 32)->nullable();
			$table->integer('gnz_family_member_number')->nullable();
			$table->date('resigned')->nullable();
			$table->text('previous_clubs', 65535)->nullable();
			$table->text('resigned_comment', 65535)->nullable();
			$table->boolean('instructor')->nullable();
			$table->string('instructor_rating', 8)->nullable();
			$table->boolean('aero_tow')->nullable();
			$table->boolean('winch_rating')->nullable();
			$table->boolean('self_launch')->nullable();
			$table->string('insttrain', 32)->nullable();
			$table->string('observer_number', 32)->nullable();
			$table->boolean('tow_pilot')->nullable();
			$table->text('awards', 65535)->nullable();
			$table->integer('qgp_number')->nullable();
			$table->date('date_of_qgp')->nullable();
			$table->integer('silver_certificate_number')->nullable();
			$table->boolean('silver_duration')->nullable();
			$table->boolean('silver_distance')->nullable();
			$table->boolean('silver_height')->nullable();
			$table->integer('gold_badge_number')->nullable();
			$table->boolean('gold_distance')->nullable();
			$table->boolean('gold_height')->nullable();
			$table->integer('diamond_distance_number')->nullable();
			$table->integer('diamond_height_number')->nullable();
			$table->integer('diamond_goal_number')->nullable();
			$table->integer('all_3_diamonds_number')->nullable();
			$table->integer('flight_1000km_number')->nullable();
			$table->integer('flight_1250km_number')->nullable();
			$table->integer('flight_1500km_number')->nullable();
			$table->boolean('pending_approval')->default(0);
			$table->text('comments', 65535)->nullable();
			$table->boolean('instructor_trainer')->nullable();
			$table->boolean('tow_pilot_instructor')->nullable();
			$table->boolean('aero_instructor')->nullable();
			$table->boolean('advanced_aero_instructor')->nullable();
			$table->boolean('auto_tow')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		$env = getenv('APP_ENV');
		if ($env=='production') {
			return true;
		}
		
		Schema::drop('gnz_member');
	}

}
