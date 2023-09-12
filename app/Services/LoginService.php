<?php

namespace App\Services;
use App\Repositories\UserRepository;
use Carbon\Carbon;
use Illuminate\Support\Str;

class LoginService
{
    /**
     * @var UserRepository
     */
    protected UserRepository $userRepository;
    public function __construct (
        UserRepository $userRepository
    )
    {
        $this->userRepository = $userRepository;
    }


    public function createAccount($user) {
        $user['uuid'] = Str::uuid();
        $user['unique_id'] = Str::uuid();
        $user['created_at'] = Carbon::today();
        $user['updated_at'] = Carbon::today();
        $this->userRepository->updateOrCreate(['email' => $user['email']], $user);
        dd($user);
    }
}
