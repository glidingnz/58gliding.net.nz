<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Org;

class AddBadgeComments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('badge_member', function ($table) {
            $table->text('comments');
        });

        // fix missing GNZ code for youth
        $item = Org::where('slug', 'youthglide')->first();
        $item->gnz_code='YG';
        $item->save();

        // add extra column for contest pilots
        Schema::table('gnz_member', function(Blueprint $table)
        {
            $table->boolean('contest_pilot')->default(0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('badge_member', function ($table) {
            $table->dropColumn('comments');
        });

        // unfix missing GNZ code for youth
        $item = Org::where('slug', 'youthglide')->first();
        $item->gnz_code='';
        $item->save();


        // remove extra column for contest pilots
        Schema::table('gnz_member', function(Blueprint $table)
        {
            $table->dropColumn('contest_pilot');
        });
    }
}
