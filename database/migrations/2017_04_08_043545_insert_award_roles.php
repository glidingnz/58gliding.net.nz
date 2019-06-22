<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Role;

class InsertAwardRoles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Coaches are defined in the GNZ database. Each GNZ member can be a coach. This means we can easily list all coaches.
        Schema::table('gnz_member', function(Blueprint $table)
        {
            $table->boolean('coach');
            $table->boolean('privacy');
        });

        // The role of awards officer is in the permissions system, not the GNZ database.
        $role = new Role;
        $role->name = 'Awards Officer';
        $role->slug = 'awards-officer';
        $role->description = 'Awards Officer';
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
        Schema::table('gnz_member', function(Blueprint $table)
        {
            $table->dropColumn('coach');
            $table->dropColumn('privacy');
        });

        Role::where('slug', 'awards-officer')->delete();
    }
}
