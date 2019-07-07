<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\Traits\MakeDutyTrait;
use Tests\ApiTestTrait;

class DutyApiTest extends TestCase
{
    use MakeDutyTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_duty()
    {
        $duty = $this->fakeDutyData();
        $this->response = $this->json('POST', '/api/duties', $duty);

        $this->assertApiResponse($duty);
    }

    /**
     * @test
     */
    public function test_read_duty()
    {
        $duty = $this->makeDuty();
        $this->response = $this->json('GET', '/api/duties/'.$duty->id);

        $this->assertApiResponse($duty->toArray());
    }

    /**
     * @test
     */
    public function test_update_duty()
    {
        $duty = $this->makeDuty();
        $editedDuty = $this->fakeDutyData();

        $this->response = $this->json('PUT', '/api/duties/'.$duty->id, $editedDuty);

        $this->assertApiResponse($editedDuty);
    }

    /**
     * @test
     */
    public function test_delete_duty()
    {
        $duty = $this->makeDuty();
        $this->response = $this->json('DELETE', '/api/duties/'.$duty->id);

        $this->assertApiSuccess();
        $this->response = $this->json('GET', '/api/duties/'.$duty->id);

        $this->response->assertStatus(404);
    }
}
