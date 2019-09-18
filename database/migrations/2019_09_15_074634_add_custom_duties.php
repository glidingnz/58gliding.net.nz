<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCustomDuties extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('duties', function ($table) {
            $table->boolean('custom')->nullable()->default(0);
        });
        Schema::table('rosters', function ($table) {
            $table->integer('duty_id')->nullable();
        });
        Schema::table('rosters', function ($table) {
            $table->dropColumn('duty_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('duties', function ($table) {
            $table->dropColumn('custom');
        });
        Schema::table('rosters', function ($table) {
            $table->dropColumn('duty_id')->nullable();
        });
        Schema::table('rosters', function ($table) {
            $table->string('duty_name');
        });
    }
}
