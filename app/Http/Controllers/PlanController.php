<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PlanService;
use App\Http\Requests\PlanRequest;

class PlanController extends Controller
{
    public PlanService $planService;

    public function __construct(
        PlanService $planService
    )
    {
        $this->planService = $planService;
    }

    /**
     * @param PlanValidation $request
     * @return true
     */
    public function createPlan(PlanRequest $request): bool
    {
        $data = $request->input();
        dd($data);
        $this->planService->createPlan($data);
        return true;
    }

}
