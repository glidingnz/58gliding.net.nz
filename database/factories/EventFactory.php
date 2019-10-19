<?php
use Carbon\Carbon;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\Models\Event::class, function (Faker\Generator $faker) {

	$start_date = $faker->dateTimeBetween('now -10 days', 'now +365 days');
	$end_date =  Carbon::instance($start_date)->addDays($faker->biasedNumberBetween($min = 1, $max = 7, function($x) { return 1 - sqrt($x); }))->format('Y-m-d'); //, 'Y-m-d HH:mm:ss'

	return [
		'featured' => $faker->boolean(10),
		'type' => $faker->randomElement(['competition', 'training', 'course', 'dinner', 'bbq', 'working-bee', 'cadets', 'school-group', 'meeting', 'other']),
		'org_id' => $faker->numberBetween($min=1, $max=33),
		'name' => $faker->sentence,
		'slug' => str_random(10),
		'location' => $faker->randomElement(['Matamata', 'Taupo', 'Drury', 'Wellington', 'Omarama']),
		'start_date' => $start_date,
		'end_date' => $end_date,
		'details' => $faker->paragraph($nbSentences = 3, $variableNbSentences = true),
	];
});


