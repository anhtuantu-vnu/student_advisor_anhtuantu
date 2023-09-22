<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PlanService;
use Illuminate\Http\Response;
use App\Http\Requests\PlanRequest;
use Illuminate\View\View;

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
     * Show the application plan
     * @return View
     */
    public function index():View
    {
        $userId = "c0cf400b-b81f-4779-9a1d-12ae3978ac3a";
        $listPlan = $this->planService->getPlans($userId)->toArray();
        return view('front-end.layouts.layout_plan');
    }

    /**
     * @return View
     */
    public function showCreatePlan(): View
    {
        $userId = "c0cf400b-b81f-4779-9a1d-12ae3978ac3a";
        return view('front-end.layouts.layout_create_plan');
    }

    /**
     * @return true
     */
    public function createPlan(Request $request): bool
    {
        $data = $request->input();
        $this->planService->createPlan($data);
        return true;
    }

}
