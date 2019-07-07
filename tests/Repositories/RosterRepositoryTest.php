<?php namespace Tests\Repositories;

use App\Models\Roster;
use App\Repositories\RosterRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\Traits\MakeRosterTrait;
use Tests\ApiTestTrait;

class RosterRepositoryTest extends TestCase
{
    use MakeRosterTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var RosterRepository
     */
    protected $rosterRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->rosterRepo = \App::make(RosterRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_roster()
    {
        $roster = $this->fakeRosterData();
        $createdRoster = $this->rosterRepo->create($roster);
        $createdRoster = $createdRoster->toArray();
        $this->assertArrayHasKey('id', $createdRoster);
        $this->assertNotNull($createdRoster['id'], 'Created Roster must have id specified');
        $this->assertNotNull(Roster::find($createdRoster['id']), 'Roster with given id must be in DB');
        $this->assertModelData($roster, $createdRoster);
    }

    /**
     * @test read
     */
    public function test_read_roster()
    {
        $roster = $this->makeRoster();
        $dbRoster = $this->rosterRepo->find($roster->id);
        $dbRoster = $dbRoster->toArray();
        $this->assertModelData($roster->toArray(), $dbRoster);
    }

    /**
     * @test update
     */
    public function test_update_roster()
    {
        $roster = $this->makeRoster();
        $fakeRoster = $this->fakeRosterData();
        $updatedRoster = $this->rosterRepo->update($fakeRoster, $roster->id);
        $this->assertModelData($fakeRoster, $updatedRoster->toArray());
        $dbRoster = $this->rosterRepo->find($roster->id);
        $this->assertModelData($fakeRoster, $dbRoster->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_roster()
    {
        $roster = $this->makeRoster();
        $resp = $this->rosterRepo->delete($roster->id);
        $this->assertTrue($resp);
        $this->assertNull(Roster::find($roster->id), 'Roster should not exist in DB');
    }
}
