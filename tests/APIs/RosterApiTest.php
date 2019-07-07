<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\Traits\MakeRosterTrait;
use Tests\ApiTestTrait;

class RosterApiTest extends TestCase
{
    use MakeRosterTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_roster()
    {
        $roster = $this->fakeRosterData();
        $this->response = $this->json('POST', '/api/roster', $roster);

        $this->assertApiResponse($roster);
    }

    /**
     * @test
     */
    public function test_read_roster()
    {
        $roster = $this->makeRoster();
        $this->response = $this->json('GET', '/api/roster/'.$roster->id);

        $this->assertApiResponse($roster->toArray());
    }

    /**
     * @test
     */
    public function test_update_roster()
    {
        $roster = $this->makeRoster();
        $editedRoster = $this->fakeRosterData();

        $this->response = $this->json('PUT', '/api/roster/'.$roster->id, $editedRoster);

        $this->assertApiResponse($editedRoster);
    }

    /**
     * @test
     */
    public function test_delete_roster()
    {
        $roster = $this->makeRoster();
        $this->response = $this->json('DELETE', '/api/roster/'.$roster->id);

        $this->assertApiSuccess();
        $this->response = $this->json('GET', '/api/roster/'.$roster->id);

        $this->response->assertStatus(404);
    }
}
