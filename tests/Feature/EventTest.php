<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class EventTest extends TestCase
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
     * Test api get user events success
     */
    public function test_get_user_events(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->get('/api/user-events');

        $response->assertStatus(200);
        $this->assertArrayHasKey('events', $response->decodeResponseJson()['data']);
    }
}
