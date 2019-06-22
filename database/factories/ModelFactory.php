<?php

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

$factory->define(App\User::class, function (Faker\Generator $faker) {
	static $password;

	return [
		'first_name' => $faker->firstName,
		'last_name' => $faker->lastName,
		'email' => $faker->safeEmail,
		'password' => $password ?: $password = bcrypt('secret'),
		'remember_token' => str_random(10),
		'activation_code' => str_random(12),
		'activated' => $faker->boolean(75),
		'mobile' => $faker->numerify('021 ### ####'),
		'gnz_id' => null,
		'gnz_active' => false,
	];
});


$factory->define(App\Models\Member::class, function (Faker\Generator $faker) {

	return [
		'first_name' => $faker->firstName,
		'middle_name' => $faker->firstName,
		'last_name' => $faker->lastName,
		'nzga_number' => $faker->unique()->randomNumber(4),
		'login_name' => $faker->unique()->randomNumber(4),
		'gender' => $faker->randomElement(array('', 'M', 'F')),
		'email' => $faker->safeEmail,
		'access_level' => 'NORMAL',
		'membership_type' => $faker->randomElement(array('Flying', 'Resigned', 'Flying Family', 'Mag Only')),
		'club' => $faker->optional(0.6)->randomElement(array('TGA', 'OGC', 'PKO', 'TPO')),
		'address_1' => $faker->streetName,
		'address_2' => $faker->state,
		'country' => 'NZ',
		'zip_post' => $faker->numerify('####'),
		'mobile_phone' => $faker->numerify('021 ### ####'),
		'home_phone' => $faker->numerify('0# ### ####'),
		'instructor' => $faker->boolean(20),
		'tow_pilot' => $faker->boolean(20),
		'self_launch' => $faker->boolean(20),
		'auto_tow' => $faker->boolean(20),
		'advanced_aero_instructor' => $faker->boolean(10),
		'aero_instructor' => $faker->boolean(10),
		'tow_pilot_instructor' => $faker->boolean(10),
		'instructor_trainer' => $faker->boolean(10),
		'qgp_number' => $faker->unique()->randomNumber(4),
		'silver_duration' => $faker->boolean(10),
		'silver_distance' => $faker->boolean(10),
		'silver_height' => $faker->boolean(10),
		'gold_distance' => $faker->boolean(10),
		'gold_height' => $faker->boolean(10),
		'silver_certificate_number' => $faker->optional(0.1)->randomNumber(3),
		'gold_badge_number' => $faker->optional(0.1)->randomNumber(3),
		'diamond_distance_number' => $faker->optional(0.1)->randomNumber(3),
		'diamond_height_number' => $faker->optional(0.1)->randomNumber(3),
		'diamond_goal_number' => $faker->optional(0.1)->randomNumber(3),
		'all_3_diamonds_number' => $faker->optional(0.1)->randomNumber(3),
		'flight_1000km_number' => $faker->optional(0.1)->randomNumber(3),
		'flight_1250km_number' => $faker->optional(0.1)->randomNumber(3),
		'flight_1500km_number' => $faker->optional(0.1)->randomNumber(3),
		'date_of_qgp' => $faker->optional(0.2)->dateTimeBetween($startDate = '-10 years'),
		'qgp_number' => $faker->optional(0.2)->randomNumber(3),
		'observer_number' => $faker->optional(0.2)->numerify('##/###'),
		'date_of_birth' => $faker->dateTimeBetween($startDate = '-70 years'),
		'date_joined' => $faker->dateTimeBetween($startDate = '-70 years'),
	];
});
