<?php namespace Tests\Traits;

use Faker\Factory as Faker;
use App\Models\duties;
use App\Repositories\dutiesRepository;

trait MakedutiesTrait
{
    /**
     * Create fake instance of duties and save it in database
     *
     * @param array $dutiesFields
     * @return duties
     */
    public function makeduties($dutiesFields = [])
    {
        /** @var dutiesRepository $dutiesRepo */
        $dutiesRepo = \App::make(dutiesRepository::class);
        $theme = $this->fakedutiesData($dutiesFields);
        return $dutiesRepo->create($theme);
    }

    /**
     * Get fake instance of duties
     *
     * @param array $dutiesFields
     * @return duties
     */
    public function fakeduties($dutiesFields = [])
    {
        return new duties($this->fakedutiesData($dutiesFields));
    }

    /**
     * Get fake data of duties
     *
     * @param array $dutiesFields
     * @return array
     */
    public function fakedutiesData($dutiesFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'org_id' => $fake->randomDigitNotNull,
            'name' => $fake->text
        ], $dutiesFields);
    }
}
