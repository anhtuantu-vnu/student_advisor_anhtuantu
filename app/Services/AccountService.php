<?php

namespace App\Services;
use App\Repositories\UserRepository;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class AccountService
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


    /**
     * Save data count when login by google or register by api
     * @param $user
     * @return mixed
     */
    public function createAccount($user): mixed
    {
        $condition = ['email' => $user['email']];
        $user['created_at'] = Carbon::today();
        $user['updated_at'] = Carbon::today();
        $users = $this->userRepository->findOne($condition);
        if (empty($users)) {
            $user['uuid'] = Str::uuid();
            $user['unique_id'] = Str::uuid();
            return $this->userRepository->create($user);
        }
        return $this->userRepository->updateByCondition($condition, $user);
    }

    /**
     * Check data login
     * @param $email
     * @param $password
     * @return mixed
     */
    public function loginAccount($email, $password): mixed
    {
        $account = $this->userRepository->findOne(['email' => $email]);
        if ($account && Hash::check($password, $account->password)) {
            return $account->toArray();
        }
        return false;
    }
}
