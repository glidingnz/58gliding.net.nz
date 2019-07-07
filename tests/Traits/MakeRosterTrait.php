<?php namespace Tests\Traits;

use Faker\Factory as Faker;
use App\Models\Roster;
use App\Repositories\RosterRepository;

trait MakeRosterTrait
{
    /**
     * Create fake instance of Roster and save it in database
     *
     * @param array $rosterFields
     * @return Roster
     */
    public function makeRoster($rosterFields = [])
    {
        /** @var RosterRepository $rosterRepo */
        $rosterRepo = \App::make(RosterRepository::class);
        $theme = $this->fakeRosterData($rosterFields);
        return $rosterRepo->create($theme);
    }

    /**
     * Get fake instance of Roster
     *
     * @param array $rosterFields
     * @return Roster
     */
    public function fakeRoster($rosterFields = [])
    {
        return new Roster($this->fakeRosterData($rosterFields));
    }

    /**
     * Get fake data of Roster
     *
     * @param array $rosterFields
     * @return array
     */
    public function fakeRosterData($rosterFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'org_id' => $fake->randomDigitNotNull,
            'day_id' => $fake->randomDigitNotNull,
            'day_date' => $fake->word,
            'dayrole_id' => $fake->randomDigitNotNull,
            'member_id' => $fake->randomDigitNotNull,
            'duty_name' => $fake->text,
            'helper_name' => $fake->text,
            'helper_mobile' => $fake->text,
            'deleted_at' => $fake->date('Y-m-d H:i:s'),
            'created_at' => $fake->date('Y-m-d H:i:s'),
            'updated_at' => $fake->date('Y-m-d H:i:s')
        ], $rosterFields);
    }
}
