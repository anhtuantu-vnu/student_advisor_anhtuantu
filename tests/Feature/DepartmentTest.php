<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class DepartmentTest extends TestCase
{
    protected $targetUser;
    protected $token;

    public function setUp(): void
    {
        parent::setUp();
        $this->targetUser = User::where('email', '=', '1001@gmail.com')->first();

        $this->token = $this->post('/api/login', [
            'email' => $this->targetUser->email,
            'password' => 'Admin123',
        ])->decodeResponseJson()['data']['authorization']['token'];
    }

    /**
     * Test get departments API
     */
    public function test_get_departments(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->get('/api/departments');
        $response->assertStatus(200);
        $this->assertArrayHasKey('departments', $response->decodeResponseJson()['data']);
    }
}
