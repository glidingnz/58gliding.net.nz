<?php
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use App\User;
use App\Models\Event;
use App\Classes\LoadAircraft;

class DatabaseSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$env = getenv('APP_ENV');
		Model::unguard();
		DB::statement('SET FOREIGN_KEY_CHECKS=0;');
		
		// local enviroment only ------------------
		if ($env=='local')
		{
			factory(User::Class, 20)->create();
		}
		
		// all enviroments including production ------------------
		//$aircraftLoader = new LoadAircraft();
		//$aircraftLoader->load_db_from_caa();
		//$aircraftLoader->import_caa_db();

		$this->call(GNZMemberSeeder::class);

		DB::statement('SET FOREIGN_KEY_CHECKS=1;');
		Model::reguard();

		// load a heap of events
		factory(Event::class, 500)->create();

	}
}
