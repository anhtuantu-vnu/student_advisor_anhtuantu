<?php

namespace Tests\Feature;

use App\Http\Controllers\_CONST;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

use App\Models\User;

class AuthTest extends TestCase
{
    protected $userTest;

    public function setUp(): void
    {
        parent::setUp();

        $this->userTest = [
            'last_name' => 'Nguyen',
            'first_name' => 'Anh Tuan',
            'email' => 'anhtuantu@test.com',
            'uuid' => Str::uuid(),
            'unique_id' => 'anhtuantu_test',
            'role' => _CONST::STUDENT_ROLE,
            'password' => Hash::make('Admin123'),
        ];
    }

    public function tearDown(): void
    {
        User::where('email', 'anhtuantu@test.com')->delete();
        parent::tearDown();
    }

    /**
     * Test login api success
     */
    public function test_login(): void
    {
        User::create($this->userTest);

        $response = $this->post('/api/login', [
            'email' => 'anhtuantu@test.com',
            'password' => 'Admin123',
        ]);

        $response->assertStatus(200);
    }

    /**
     * Test login api fail
     */
    public function test_login2(): void
    {
        User::create($this->userTest);

        $response = $this->post('/api/login', [
            'email' => 'anhtuantu@test.com',
            'password' => 'Admin1234',
        ]);

        $response->assertStatus(401);
    }
}
