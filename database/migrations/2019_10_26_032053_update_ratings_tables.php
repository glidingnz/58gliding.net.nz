<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateRatingsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Add soft deletes
        Schema::table('rating_member', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::create('uploads', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id'); // user that uploads the file
            $table->integer('org_id'); // org the file belongs to
            $table->string('filename')->nullable(); // the original uploaded filename
            $table->string('folder')->nullable(); // the folder the file is in
            $table->string('slug')->nullable(); // the URL version of the name
            $table->string('icon')->nullable(); // the icon to display
            $table->string('type')->nullable(); // the type of file e.g. pdf, docx, txt, zip
            $table->string('uploadable_type')->nullable(); // Laravel polymorphic type string
            $table->integer('uploadable_id')->nullable(); // Laravel polymorphic ID
            $table->text('description')->nullable(); // general description for the file
            $table->softDeletes();
            $table->timestamps();

        });

        // Schema::create('rating_member_upload', function (Blueprint $table) {
        //     $table->softDeletes();
        //     $table->integer('rating_member_id');
        //     $table->integer('upload_id');
        //     $table->timestamps();
        // });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        if (Schema::hasTable('rating_member')) {
            Schema::table('rating_member', function (Blueprint $table) {
                $table->dropColumn('deleted_at');
            });
        }
        if (Schema::hasTable('uploads')) {
            Schema::drop('uploads');
        }
        //Schema::drop('rating_member_upload');
    }
}
