<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TestService;
class TestController extends Controller
{
    /**
     * @var TestService
     */
    protected TestService $testService;
    public function __construct(
        TestService $testService
    )
    {
        $this->testService = $testService;
    }

    /**
     * @return void
     */
    public function test() {
        return $this->testService->testFunction();
    }
}
