<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\Traits\MakedutiesTrait;
use Tests\ApiTestTrait;

class dutiesApiTest extends TestCase
{
    use MakedutiesTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_duties()
    {
        $duties = $this->fakedutiesData();
        $this->response = $this->json('POST', '/api/duties', $duties);

        $this->assertApiResponse($duties);
    }

    /**
     * @test
     */
    public function test_read_duties()
    {
        $duties = $this->makeduties();
        $this->response = $this->json('GET', '/api/duties/'.$duties->id);

        $this->assertApiResponse($duties->toArray());
    }

    /**
     * @test
     */
    public function test_update_duties()
    {
        $duties = $this->makeduties();
        $editedduties = $this->fakedutiesData();

        $this->response = $this->json('PUT', '/api/duties/'.$duties->id, $editedduties);

        $this->assertApiResponse($editedduties);
    }

    /**
     * @test
     */
    public function test_delete_duties()
    {
        $duties = $this->makeduties();
        $this->response = $this->json('DELETE', '/api/duties/'.$duties->id);

        $this->assertApiSuccess();
        $this->response = $this->json('GET', '/api/duties/'.$duties->id);

        $this->response->assertStatus(404);
    }
}
