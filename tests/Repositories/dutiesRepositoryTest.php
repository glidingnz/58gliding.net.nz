<?php namespace Tests\Repositories;

use App\Models\duties;
use App\Repositories\dutiesRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\Traits\MakedutiesTrait;
use Tests\ApiTestTrait;

class dutiesRepositoryTest extends TestCase
{
    use MakedutiesTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var dutiesRepository
     */
    protected $dutiesRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->dutiesRepo = \App::make(dutiesRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_duties()
    {
        $duties = $this->fakedutiesData();
        $createdduties = $this->dutiesRepo->create($duties);
        $createdduties = $createdduties->toArray();
        $this->assertArrayHasKey('id', $createdduties);
        $this->assertNotNull($createdduties['id'], 'Created duties must have id specified');
        $this->assertNotNull(duties::find($createdduties['id']), 'duties with given id must be in DB');
        $this->assertModelData($duties, $createdduties);
    }

    /**
     * @test read
     */
    public function test_read_duties()
    {
        $duties = $this->makeduties();
        $dbduties = $this->dutiesRepo->find($duties->id);
        $dbduties = $dbduties->toArray();
        $this->assertModelData($duties->toArray(), $dbduties);
    }

    /**
     * @test update
     */
    public function test_update_duties()
    {
        $duties = $this->makeduties();
        $fakeduties = $this->fakedutiesData();
        $updatedduties = $this->dutiesRepo->update($fakeduties, $duties->id);
        $this->assertModelData($fakeduties, $updatedduties->toArray());
        $dbduties = $this->dutiesRepo->find($duties->id);
        $this->assertModelData($fakeduties, $dbduties->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_duties()
    {
        $duties = $this->makeduties();
        $resp = $this->dutiesRepo->delete($duties->id);
        $this->assertTrue($resp);
        $this->assertNull(duties::find($duties->id), 'duties should not exist in DB');
    }
}
