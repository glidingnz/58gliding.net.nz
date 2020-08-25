<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Role;

class InsertRoleForExperimentalFeatures extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

		//insert default roles
		$role = new Role;
		$role->name = 'Experimental Features';
		$role->slug = 'experimental-features';
		$role->description = 'Role required to interact with experimental features';
		$role->club=false;
		$role->save();
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Role::where('slug', 'experimental-features')->delete();
	}
}
