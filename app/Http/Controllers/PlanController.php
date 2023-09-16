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

    public function createPlan(PlanValidation $request)
    {
        $dataPlan = $request->input();
        try {
            $this->formValidation->validate($dataPlan);
            $this->planService->createPlan($dataPlan);
        }  catch (BaseValidationException $e) {
            dd($e);
        }
        return true;
    }

}
