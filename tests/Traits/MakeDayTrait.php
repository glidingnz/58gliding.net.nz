<?php namespace Tests\Traits;

use Faker\Factory as Faker;
use App\Models\Day;
use App\Repositories\DayRepository;

trait MakeDayTrait
{
    /**
     * Create fake instance of Day and save it in database
     *
     * @param array $dayFields
     * @return Day
     */
    public function makeDay($dayFields = [])
    {
        /** @var DayRepository $dayRepo */
        $dayRepo = \App::make(DayRepository::class);
        $theme = $this->fakeDayData($dayFields);
        return $dayRepo->create($theme);
    }

    /**
     * Get fake instance of Day
     *
     * @param array $dayFields
     * @return Day
     */
    public function fakeDay($dayFields = [])
    {
        return new Day($this->fakeDayData($dayFields));
    }

    /**
     * Get fake data of Day
     *
     * @param array $dayFields
     * @return array
     */
    public function fakeDayData($dayFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'org_id' => $fake->randomDigitNotNull,
            'day_date' => $fake->word,
            'active' => $fake->word,
            'description' => $fake->text,
            'trialflights' => $fake->word,
            'competition' => $fake->word,
            'training' => $fake->word,
            'winching' => $fake->word,
            'towing' => $fake->word,
            'status' => $fake->word,
            'cancelled_reason' => $fake->word,
            'deleted_at' => $fake->date('Y-m-d H:i:s')
        ], $dayFields);
    }
}
