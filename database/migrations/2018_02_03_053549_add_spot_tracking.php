<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSpotTracking extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('aircraft', function ($table) {
            $table->char('spot_feed_id', 34);
            $table->char('spot_esn', 18);
            $table->dateTime('spot_active', null)->nullable();
            $table->char('particle_id', 24);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // remove extra column for contest pilots
        Schema::table('aircraft', function(Blueprint $table)
        {
            $table->dropColumn('spot_feed_id');
            $table->dropColumn('spot_esn');
            $table->dropColumn('spot_active');
            $table->dropColumn('particle_id');
        });
    }
}