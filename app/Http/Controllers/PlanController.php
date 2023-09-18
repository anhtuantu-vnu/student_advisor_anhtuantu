<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PlanService;
use App\Http\Requests\PlanValidation;

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
    public function createPlan(PlanValidation $request): bool
    {
        dd(231231);
        $validated = $request->validated();
        $dataPlan = $request->input(['name']);
        dd($dataPlan, 123);
        //$this->planService->createPlan($dataPlan);
        return true;
    }

}
