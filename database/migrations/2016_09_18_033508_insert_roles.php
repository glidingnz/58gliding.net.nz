<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Role;
use App\Models\RoleUser;
use App\User;

class InsertRoles extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// insert the default root user
		$user = new User;
		$user->first_name='Root';
		$user->last_name='';
		$user->email='root@gliding.co.nz';
		$user->password=bcrypt('root');
		$user->activated=1;
		$user->save();

		//insert default roles
		$role = new Role;
		$role->name = 'Root';
		$role->slug = 'root';
		$role->description = 'Everything';
		$role->club=false;
		$role->save();

		$role = new Role;
		$role->name = 'Admin';
		$role->slug = 'admin';
		$role->description = 'Gobal Administrator';
		$role->club=false;
		$role->save();

		$role = new Role;
		$role->name = 'Club Admin';
		$role->slug = 'club-admin';
		$role->description = 'Club Administrator';
		$role->club=true;
		$role->save();

		$role = new Role;
		$role->name = 'Club Member';
		$role->slug = 'club-member';
		$role->description = 'Club Member';
		$role->club=true;
		$role->save();

		// grant the admin user a default root role
		$rootRole = Role::where('slug','root')->first();
		$adminUser = User::where('email', 'root@gliding.co.nz')->first()->roles()->attach($rootRole->id);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		User::where('email', 'root@gliding.co.nz')->delete();
		Role::where('slug', 'root')->delete();
		Role::where('slug', 'admin')->delete();
		Role::where('slug', 'club-admin')->delete();
		Role::where('slug', 'club-member')->delete();
	}
}
