<?php

namespace App\Services;
use App\Repositories\UserRepository;

class TestService
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
     * @return void
     */
    public function testFunction ()
    {
        return $this->userRepository->all();
    }
}
