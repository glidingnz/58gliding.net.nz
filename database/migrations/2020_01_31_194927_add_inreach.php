<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInreach extends Migration
{
    /**
     * Run the migrations. 
     *
     * @return void
     */
    public function up()
    {
        //
        //
        Schema::table('aircraft', function ($table) {
            $table->string('inreach_share')->nullable()->default(null);
            $table->string('inreach_imei')->nullable()->default(null);
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
        Schema::table('aircraft', function ($table) {
            $table->dropColumn('inreach_share');
            $table->dropColumn('inreach_imei');
        });
    }
}
