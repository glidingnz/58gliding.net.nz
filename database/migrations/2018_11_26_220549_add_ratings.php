<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Rating;

class AddRatings extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// create tables
		Schema::create('ratings', function (Blueprint $table) {
			$table->increments('id');
			$table->string('name');
			$table->integer('default_expires')->nullable()->default(24);
			$table->timestamps();
		});

		Schema::create('rating_member', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('rating_id');
			$table->integer('member_id');
			$table->date('awarded');
			$table->date('expires')->nullable()->default(NULL);
			$table->text('notes');
			$table->integer('authorising_member_id');
			$table->integer('granted_by_user_id');
			$table->string('revoked_by');
			$table->timestamps();
		});

		// insert default ratings
		$rating = new Rating;
		$rating->name = "QGP";
		$rating->default_expires = null;
		$rating->save();

		$rating = new Rating;
		$rating->name = "BFR";
		$rating->default_expires = "24";
		$rating->save();

		$rating = new Rating;
		$rating->name = "GNZ Medical";
		$rating->default_expires = null;
		$rating->save();

		$rating = new Rating;
		$rating->name = "Class 1 Medical";
		$rating->default_expires = "12";
		$rating->save();

		$rating = new Rating;
		$rating->name = "Class 2 Medical";
		$rating->default_expires = "60";
		$rating->save();

		$rating = new Rating;
		$rating->name = "Class 3 Medical";
		$rating->default_expires = "60";
		$rating->save();

		$rating = new Rating;
		$rating->name = "NZTA Medical DL9";
		$rating->default_expires = "24";
		$rating->save();

		$rating = new Rating;
		$rating->name = "Passenger";
		$rating->default_expires = "24";
		$rating->save();


		$rating = new Rating;
		$rating->name = "Instructor A Cat";
		$rating->default_expires = "24";
		$rating->save();

		$rating = new Rating;
		$rating->name = "Instructor B Cat";
		$rating->default_expires = "24";
		$rating->save();

		$rating = new Rating;
		$rating->name = "Instructor C Cat";
		$rating->default_expires = "24";
		$rating->save();

		$rating = new Rating;
		$rating->name = "Instructor D Cat";
		$rating->default_expires = "24";
		$rating->save();

		$rating = new Rating;
		$rating->name = "Instructor Trainer";
		$rating->default_expires = "24";
		$rating->save();

		$rating = new Rating;
		$rating->name = "Front Seat Passenger";
		$rating->default_expires = null;
		$rating->save();

		$rating = new Rating;
		$rating->name = "Back Seat Passenger";
		$rating->default_expires = null;
		$rating->save();

		$rating = new Rating;
		$rating->name = "Cross Country";
		$rating->default_expires = null;
		$rating->save();

		$rating = new Rating;
		$rating->name = "Ridge Flying";
		$rating->default_expires = null;
		$rating->save();

		$rating = new Rating;
		$rating->name = "Mountain Flying";
		$rating->default_expires = null;
		$rating->save();

		$rating = new Rating;
		$rating->name = "Aerotow Launch";
		$rating->default_expires = null;
		$rating->save();

		$rating = new Rating;
		$rating->name = "Winch Launch";
		$rating->default_expires = null;
		$rating->save();

		$rating = new Rating;
		$rating->name = "Auto Launch";
		$rating->default_expires = null;
		$rating->save();

		$rating = new Rating;
		$rating->name = "Aerobatic Flight Rating";
		$rating->default_expires = null;
		$rating->save();

		$rating = new Rating;
		$rating->name = "A Syllabus";
		$rating->default_expires = null;
		$rating->save();

		$rating = new Rating;
		$rating->name = "B Syllabus";
		$rating->default_expires = null;
		$rating->save();

		$rating = new Rating;
		$rating->name = "PPL";
		$rating->default_expires = 60;
		$rating->save();

		$rating = new Rating;
		$rating->name = "CPL";
		$rating->default_expires = 60;
		$rating->save();

		$rating = new Rating;
		$rating->name = "Tow Pilot";
		$rating->default_expires = 24;
		$rating->save();

		$rating = new Rating;
		$rating->name = "Winch Driver";
		$rating->default_expires = 24;
		$rating->save();

		$rating = new Rating;
		$rating->name = "Powered Glider";
		$rating->default_expires = "24";
		$rating->save();

		$rating = new Rating;
		$rating->name = "IMC";
		$rating->default_expires = "24";
		$rating->save();

		$rating = new Rating;
		$rating->name = "Radio Operators Certificate";
		$rating->default_expires = null;
		$rating->save();

		$rating = new Rating;
		$rating->name = "Aerobatic Instructor (Aero)";
		$rating->default_expires = null;
		$rating->save();

		$rating = new Rating;
		$rating->name = "Aerobatic Instructor (Advanced Aero)";
		$rating->default_expires = null;
		$rating->save();


	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('ratings');
		Schema::drop('rating_member');
	}
}
