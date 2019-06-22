<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Badge;

class CreateBadgesTables extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// create badges table
		Schema::create('badges', function (Blueprint $table) {
			$table->increments('id');
			$table->string('name')->default('');
			$table->string('type')->default('');
			$table->string('slug')->default('');
			$table->timestamps();
		});

		// create badges to member table
		Schema::create('badge_member', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('member_id')->unsigned()->index();
			$table->integer('badge_id')->unsigned()->index();
			$table->date('awarded_date')->nullable();
			$table->string('olc_link')->default('')->nullable();
			$table->string('badge_number')->default('')->nullable();
			$table->timestamps();
		});




		// create default badge types

		$badge = new Badge;
		$badge->name='Dual Cross Country';
		$badge->type='General';
		$badge->slug='dual-xc';
		$badge->save();

		$badge = new Badge;
		$badge->name='QGP';
		$badge->type='General';
		$badge->slug='qgp';
		$badge->save();

		$badge = new Badge;
		$badge->name='OLC Registered';
		$badge->type='OLC';
		$badge->slug='olc-registered';
		$badge->save();

		$badge = new Badge;
		$badge->name='OLC 50km';
		$badge->type='OLC';
		$badge->slug='olc-50km';
		$badge->save();

		$badge = new Badge;
		$badge->name='OLC 100km';
		$badge->type='OLC';
		$badge->slug='olc-100km';
		$badge->save();

		$badge = new Badge;
		$badge->name='5 Outlandings';
		$badge->type='Outlanding';
		$badge->slug='5-outlanding';
		$badge->save();

		$badge = new Badge;
		$badge->name='FAI Silver 50km';
		$badge->type='FAI Badges';
		$badge->slug='fai-silver-50km';
		$badge->save();

		$badge = new Badge;
		$badge->name='FAI Silver Height Gain 1000m';
		$badge->type='FAI Badges';
		$badge->slug='fai-silver-1000m';
		$badge->save();

		$badge = new Badge;
		$badge->name='FAI Silver Badge';
		$badge->type='FAI Badges';
		$badge->slug='fai-silver-badge';
		$badge->save();

		$badge = new Badge;
		$badge->name='OLC 200km';
		$badge->type='FAI Badges';
		$badge->slug='olc-200km';
		$badge->save();

		$badge = new Badge;
		$badge->name='OLC 300km';
		$badge->type='FAI Badges';
		$badge->slug='olc-300km';
		$badge->save();

		$badge = new Badge;
		$badge->name='10 Outlandings';
		$badge->type='Outlanding';
		$badge->slug='10-outlandings';
		$badge->save();

		$badge = new Badge;
		$badge->name='FAI Gold 300km';
		$badge->type='FAI Badges';
		$badge->slug='fai-gold-300';
		$badge->save();

		$badge = new Badge;
		$badge->name='FAI Silver/Gold 5 Hour';
		$badge->type='FAI Badges';
		$badge->slug='fai-5hr';
		$badge->save();

		$badge = new Badge;
		$badge->name='FAI Gold Height Gain 3000m';
		$badge->type='FAI Badges';
		$badge->slug='fai-gold-3000m';
		$badge->save();

		$badge = new Badge;
		$badge->name='FAI Gold Badge';
		$badge->type='FAI Badges';
		$badge->slug='fai-gold-badge';
		$badge->save();

		$badge = new Badge;
		$badge->name='OLC 400km';
		$badge->type='OLC';
		$badge->slug='olc-400km';
		$badge->save();

		$badge = new Badge;
		$badge->name='OLC 500km';
		$badge->type='OLC';
		$badge->slug='olc-500km';
		$badge->save();

		$badge = new Badge;
		$badge->name='20 Outlandings';
		$badge->type='Outlanding';
		$badge->slug='20-outlandings';
		$badge->save();

		$badge = new Badge;
		$badge->name='First Contest';
		$badge->type='Contest';
		$badge->slug='first-contest';
		$badge->save();

		$badge = new Badge;
		$badge->name='First Regionals';
		$badge->type='Contest';
		$badge->slug='first-regionals';
		$badge->save();

		$badge = new Badge;
		$badge->name='First Nationals';
		$badge->type='Contest';
		$badge->slug='first-nationals';
		$badge->save();

		$badge = new Badge;
		$badge->name='Contest Day Win';
		$badge->type='Contest';
		$badge->slug='contest-day-win';
		$badge->save();

		$badge = new Badge;
		$badge->name='Regionals Day Win';
		$badge->type='Contest';
		$badge->slug='regionals-day-win';
		$badge->save();

		$badge = new Badge;
		$badge->name='Nationals Day Win';
		$badge->type='Contest';
		$badge->slug='nationals-day-win';
		$badge->save();

		$badge = new Badge;
		$badge->name='Contest Win';
		$badge->type='Contest';
		$badge->slug='nationals-win';
		$badge->save();

		$badge = new Badge;
		$badge->name='Regionals Win';
		$badge->type='Contest';
		$badge->slug='regionals-win';
		$badge->save();

		$badge = new Badge;
		$badge->name='Nationals Win';
		$badge->type='Contest';
		$badge->slug='nationals-win';
		$badge->save();

		$badge = new Badge;
		$badge->name='FAI Diamond Free Distance 500km';
		$badge->type='FAI Badges';
		$badge->slug='fai-diamond-500km';
		$badge->save();

		$badge = new Badge;
		$badge->name='FAI Diamond Goal Distance 300km';
		$badge->type='FAI Badges';
		$badge->slug='fai-diamond-300km';
		$badge->save();

		$badge = new Badge;
		$badge->name='FAI Diamond Height 5000m';
		$badge->type='FAI Badges';
		$badge->slug='fai-diamond-5000m';
		$badge->save();

		$badge = new Badge;
		$badge->name='FAI 3 Diamond Badge';
		$badge->type='FAI Badges';
		$badge->slug='fai-3-diamond';
		$badge->save();

		$badge = new Badge;
		$badge->name='FAI 750km';
		$badge->type='FAI Badges';
		$badge->slug='fai-750km';
		$badge->save();

		$badge = new Badge;
		$badge->name='FAI 1000km';
		$badge->type='FAI Badges';
		$badge->slug='fai-1000km';
		$badge->save();

		$badge = new Badge;
		$badge->name='FAI 1250km';
		$badge->type='FAI Badges';
		$badge->slug='fai-1250km';
		$badge->save();

		$badge = new Badge;
		$badge->name='FAI 1500km';
		$badge->type='FAI Badges';
		$badge->slug='fai-1500km';
		$badge->save();

		$badge = new Badge;
		$badge->name='FAI 1750km';
		$badge->type='FAI Badges';
		$badge->slug='fai-1750km';
		$badge->save();

		$badge = new Badge;
		$badge->name='FAI 2000km';
		$badge->type='FAI Badges';
		$badge->slug='fai-2000km';
		$badge->save();

		$badge = new Badge;
		$badge->name='FAI 2000km';
		$badge->type='FAI Badges';
		$badge->slug='fai-2000km';
		$badge->save();

		$badge = new Badge;
		$badge->name='FAI Paul Tissandier Diploma';
		$badge->type='FAI Awards';
		$badge->slug='fai-paul-tissandier';
		$badge->save();

		$badge = new Badge;
		$badge->name='FAI The Lilienthal Gliding Medal';
		$badge->type='FAI Awards';
		$badge->slug='fai-lilienthal';
		$badge->save();

		$badge = new Badge;
		$badge->name='FAI The Pelagia Majewska Gliding Medal';
		$badge->type='FAI Awards';
		$badge->slug='fai-pelagia-majewska';
		$badge->save();

		$badge = new Badge;
		$badge->name='FAI The Pirat Gehriger Diploma';
		$badge->type='FAI Awards';
		$badge->slug='fai-pirat-gehriger';
		$badge->save();
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
		DB::statement('SET FOREIGN_KEY_CHECKS=0;');
		Schema::drop('badges');
		Schema::drop('badge_member');
		DB::statement('SET FOREIGN_KEY_CHECKS=1;');
	}
}
