<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixMembershipDb extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // ->default($value)
        // Schema::connection('gnz')->table('gnz_member', function(Blueprint $table)
        // {
        //     $table->boolean('coach')->default(0)->change();
        //     $table->boolean('privacy')->default(0)->change();
        // });
        DB::connection('gnz')->select("ALTER TABLE gnz_member MODIFY COLUMN coach BOOL DEFAULT 0");
        DB::connection('gnz')->select("ALTER TABLE gnz_member MODIFY COLUMN privacy BOOL DEFAULT 0");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // no real need to remove default on downgrade. She'll be right.
    }
}
