<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMissingGnzTables extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		if (!Schema::hasTable('gnz_changelog')) {
			Schema::create('gnz_changelog', function (Blueprint $table) {
				$table->increments('id');
				$table->string('description', 255)->nullable();
				$table->string('action', 20)->nullable();
				$table->integer('id_member')->nullable();
				$table->integer('id_user')->nullable();
				$table->string('field', 45)->nullable();
				$table->string('oldval', 255)->nullable();
				$table->string('newval', 255)->nullable();
				$table->timestamp('created')->default(\DB::raw('CURRENT_TIMESTAMP'));
			});
		}
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('gnz_changelog');
	}
}
