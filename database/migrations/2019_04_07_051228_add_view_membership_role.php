<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Role;

class AddViewMembershipRole extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        $role = new Role;
        $role->name = 'Membership Viewer';
        $role->slug = 'view-membership';
        $role->description = 'View all membership without editing access';
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
        //
        Role::where('slug', 'view-membership')->delete();
    }
}
