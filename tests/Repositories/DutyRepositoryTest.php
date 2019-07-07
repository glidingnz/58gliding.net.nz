<?php namespace Tests\Repositories;

use App\Models\Duty;
use App\Repositories\DutyRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\Traits\MakeDutyTrait;
use Tests\ApiTestTrait;

class DutyRepositoryTest extends TestCase
{
    use MakeDutyTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var DutyRepository
     */
    protected $dutyRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->dutyRepo = \App::make(DutyRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_duty()
    {
        $duty = $this->fakeDutyData();
        $createdDuty = $this->dutyRepo->create($duty);
        $createdDuty = $createdDuty->toArray();
        $this->assertArrayHasKey('id', $createdDuty);
        $this->assertNotNull($createdDuty['id'], 'Created Duty must have id specified');
        $this->assertNotNull(Duty::find($createdDuty['id']), 'Duty with given id must be in DB');
        $this->assertModelData($duty, $createdDuty);
    }

    /**
     * @test read
     */
    public function test_read_duty()
    {
        $duty = $this->makeDuty();
        $dbDuty = $this->dutyRepo->find($duty->id);
        $dbDuty = $dbDuty->toArray();
        $this->assertModelData($duty->toArray(), $dbDuty);
    }

    /**
     * @test update
     */
    public function test_update_duty()
    {
        $duty = $this->makeDuty();
        $fakeDuty = $this->fakeDutyData();
        $updatedDuty = $this->dutyRepo->update($fakeDuty, $duty->id);
        $this->assertModelData($fakeDuty, $updatedDuty->toArray());
        $dbDuty = $this->dutyRepo->find($duty->id);
        $this->assertModelData($fakeDuty, $dbDuty->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_duty()
    {
        $duty = $this->makeDuty();
        $resp = $this->dutyRepo->delete($duty->id);
        $this->assertTrue($resp);
        $this->assertNull(Duty::find($duty->id), 'Duty should not exist in DB');
    }
}
