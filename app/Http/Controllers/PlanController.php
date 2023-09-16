<?php

namespace App\Http\Controllers;

use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use App\Services\PlanService;
use App\Http\Requests\PlanValidation;

class PlanController extends Controller
{
    use ResponseTrait;
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
        $dataPlan = $request->input();
        dd($dataPlan);
        //$this->planService->createPlan($dataPlan);
        return true;
    }

}
