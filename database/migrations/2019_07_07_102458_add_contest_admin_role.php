<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Role;
use App\Models\RoleUser;
use App\User;

class AddContestAdminRole extends Migration
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
        $role->name = 'Contest Admin';
        $role->slug = 'contest-admin';
        $role->description = 'Contest Administrator';
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
        Role::where('slug', 'contest-admin')->delete();
    }
}
