<?php namespace Tests\Traits;

use Faker\Factory as Faker;
use App\Models\Duty;
use App\Repositories\DutyRepository;

trait MakeDutyTrait
{
    /**
     * Create fake instance of Duty and save it in database
     *
     * @param array $dutyFields
     * @return Duty
     */
    public function makeDuty($dutyFields = [])
    {
        /** @var DutyRepository $dutyRepo */
        $dutyRepo = \App::make(DutyRepository::class);
        $theme = $this->fakeDutyData($dutyFields);
        return $dutyRepo->create($theme);
    }

    /**
     * Get fake instance of Duty
     *
     * @param array $dutyFields
     * @return Duty
     */
    public function fakeDuty($dutyFields = [])
    {
        return new Duty($this->fakeDutyData($dutyFields));
    }

    /**
     * Get fake data of Duty
     *
     * @param array $dutyFields
     * @return array
     */
    public function fakeDutyData($dutyFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'org_id' => $fake->randomDigitNotNull,
            'name' => $fake->text,
            'deleted_at' => $fake->date('Y-m-d H:i:s'),
            'created_at' => $fake->date('Y-m-d H:i:s'),
            'updated_at' => $fake->date('Y-m-d H:i:s')
        ], $dutyFields);
    }
}
