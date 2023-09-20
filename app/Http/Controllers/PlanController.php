<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PlanService;
use Illuminate\Validation\ValidationException;
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
     * @return true
     */
    public function createPlan(Request $request): bool
    {
        $validate = $request->validate([
            'name' => "bail|required|max:255",
            'description' => 'bail|required'
        ]);
        dd(123132);
        $data = $request->input();
        $this->planService->createPlan($data);
        return true;
    }

}
