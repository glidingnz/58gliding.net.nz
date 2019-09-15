<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContestProfileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contest_profile', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('first_name',40)->nullable();
            $table->string('last_name',40)->nullable();
            $table->boolean('is_copilot')->nullable()->default(0);
            $table->string('mobile',14)->nullable();
            $table->string('email',80)->nullable();
            $table->string('address_1',100)->nullable();
            $table->string('address_2',100)->nullable();
            $table->string('address_3',100)->nullable();
            $table->string('club',80)->nullable();

            $table->string('e_contact',80)->nullable();
            $table->string('e_mobile',14)->nullable();
            $table->string('e_phone',14)->nullable();
            $table->string('e_email',80)->nullable();
            $table->string('e_address_1',100)->nullable();
            $table->string('e_address_2',100)->nullable();
            $table->string('e_address_3',100)->nullable();
            $table->string('e_relationship',80)->nullable();

            $table->string('glider',6)->nullable();
            $table->string('type',80)->nullable();
            $table->decimal('wingspan',3,1)->nullable()->default(0);
            $table->decimal('handicap',4,3)->nullable()->default(0);
            $table->boolean('winglets')->nullable()->default(0);
            $table->boolean('has_tracker')->nullable()->default(0);

            $table->string('crew_name',80)->nullable();
            $table->string('c_phone',14)->nullable();
            $table->string('car_type',40)->nullable();
            $table->string('car_color',20)->nullable();
            $table->string('car_plate',8)->nullable();
            $table->string('trailer_type',40)->nullable();
            $table->string('trailer_color',20)->nullable();
            $table->string('trailer_plate',8)->nullable();
            $table->string('crew_notes',140)->nullable();

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
        Schema::dropIfExists('contest_profile');
    }
}
