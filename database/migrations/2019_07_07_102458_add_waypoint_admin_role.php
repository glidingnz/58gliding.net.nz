<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Role;
use App\Models\RoleUser;
use App\User;

class AddWaypointAdminRole extends Migration
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
        $role->name = 'Waypoint Admin';
        $role->slug = 'waypoint-admin';
        $role->description = 'Waypoint Database Administrator';
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
        Role::where('slug', 'waypoint-admin')->delete();
    }
}
