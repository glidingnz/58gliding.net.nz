<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CalendarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('days', function(Blueprint $table)
        {
            $table->integer('id', true);
            $table->integer('org_id')->nullable();
            $table->integer('event_id')->nullable();
            $table->date('day_date');
            $table->boolean('active')->default(1);
            $table->text('description')->nullable();
            $table->boolean('trialflights')->default(1);
            $table->boolean('competition')->default(0);
            $table->boolean('training')->default(0);
            $table->boolean('winching')->default(0);
            $table->boolean('towing')->default(1);
            $table->string('status')->default('flying');
            $table->string('cancelled_reason')->default('');
            $table->softDeletes();
            $table->timestamps();
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
        Schema::drop('days');
    }
}
