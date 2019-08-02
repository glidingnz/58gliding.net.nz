<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('contest_id')->unsigned()->nullable(false);
            $table->bigInteger('classes_id')->unsigned()->nullable(false);
            $table->foreign('contest_id')->references('id')->on('contests');
            $table->foreign('classes_id')->references('id')->on('classes');

            $table->string('first_name',40)->nullable(false);
            $table->string('lastName',40)->nullable(false);
            $table->boolean('is_copilot')->default(false);
            $table->string('mobile',14)->nullable(false);
            $table->string('email',80)->nullable(false);
            $table->string('address_1',100)->nullable(false);
            $table->string('address_2',100);
            $table->string('address_3',100);
            $table->string('club',80);

            $table->string('e_contact',80)->nullable(false);
            $table->string('e_mobile',14)->nullable(false);
            $table->string('e_phone',14)->nullable(false);
            $table->string('e_email',80)->nullable(false);
            $table->string('e_address_1',100)->nullable(false);
            $table->string('e_address_2',100)->nullable();
            $table->string('e_address_3',100)->nullable();
            $table->string('e_relationship',80)->nullable();

            $table->string('glider',6)->nullable();
            $table->string('type',80)->nullable();
            $table->decimal('wingspan',3,1)->nullable();
            $table->decimal('handicap',4,3)->nullable();
            $table->boolean('winglets')->nullable();
            $table->boolean('hasTracker')->nullable();

            $table->string('crew_name',80)->nullable();
            $table->string('c_phone',14)->nullable();
            $table->string('car_type',40)->nullable();
            $table->string('car_color',20)->nullable();
            $table->string('car_plate',8)->nullable();
            $table->string('trailer_type',40)->nullable();
            $table->string('trailer_color',20)->nullable();
            $table->string('trailer_plate',8)->nullable();
            $table->string('crew_notes',140)->nullable();

            $table->boolean('declaration')->nullable(false)->default(false);
            $table->json('attribute_1')->nullable();
            $table->json('attribute_2')->nullable();
            $table->json('attribute_3')->nullable();
            $table->json('attribute_4')->nullable();
            $table->json('attribute_5')->nullable();
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
        Schema::dropIfExists('entries');
    }
}
