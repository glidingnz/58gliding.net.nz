<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAclTables extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('roles', function(Blueprint $table) {
			$table->increments('id');
			$table->string('name')->unique();
			$table->string('slug')->unique();
			$table->text('description')->nullable();
			$table->boolean('club')->default(FALSE);
			$table->timestamps();
		});

		Schema::create('role_user', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('role_id')->unsigned()->index();
			$table->integer('user_id')->unsigned()->index();
			$table->integer('org_id')->nullable();
			$table->timestamps();
		});
		Schema::table('role_user', function(Blueprint $table) {
			$table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
		});

		Schema::create('permissions', function(Blueprint $table) {
			$table->increments('id');
			$table->string('name');
			$table->string('slug')->unique();
			$table->text('description')->nullable();
			$table->timestamps();
		});

		Schema::create('permission_role', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('permission_id')->unsigned()->index();
			$table->integer('role_id')->unsigned()->index();
			$table->timestamps();
		});
		Schema::table('permission_role', function(Blueprint $table) {
			$table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
			$table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
		});

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{		
		DB::statement('SET FOREIGN_KEY_CHECKS=0;');
		Schema::drop('roles');
		Schema::drop('role_user');
		Schema::drop('permissions');
		Schema::drop('permission_role');
		DB::statement('SET FOREIGN_KEY_CHECKS=1;');

	}
}
