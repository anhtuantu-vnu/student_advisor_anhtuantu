<?php

namespace Tests\Feature;

use App\Models\Intake;
use App\Models\IntakeMember;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;

class IntakeTest extends TestCase
{
    protected $targetUser;
    protected $intakeUuidSuccess;
    protected $intakeUuidFail;
    protected $token;

    public function setUp(): void
    {
        parent::setUp();
        $this->targetUser = User::where('email', '=', '1001@gmail.com')->first();
        $this->intakeUuidSuccess = IntakeMember::where('user_id', '=', $this->targetUser->uuid)->first()->intake_id;
        $this->intakeUuidFail = IntakeMember::where('user_id', '!=', $this->targetUser->uuid)->first()->uuid;

        $this->token = $this->post('/api/login', [
            'email' => $this->targetUser->email,
            'password' => 'Admin123',
        ])->decodeResponseJson()['data']['authorization']['token'];
    }

    /**
     * Test api get user intakes success
     */
    public function test_get_user_intakes(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->get('/api/student-intakes');

        $response->assertStatus(200);
        $this->assertArrayHasKey('intakeMembers', $response->decodeResponseJson()['data']);
    }

    /**
     * Test api get intake teacher info success
     */
    public function test_get_intake_teacher_info(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->get('/api/student-intakes/' . $this->intakeUuidSuccess . '/teacher-info');

        $response->assertStatus(200);
        $this->assertArrayHasKey('intakeTeachers', $response->decodeResponseJson()['data']);
    }

    /**
     * Test api get intake teacher info fail
     */
    public function test_get_intake_teacher_info_fail(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->get('/api/student-intakes/' . $this->intakeUuidFail . '/teacher-info');

        $response->assertStatus(400);
        $this->assertEquals(404, $response->decodeResponseJson()['errors']['error_code']);
    }
}
