<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;
use App\Services\PlanService;
use App\Repositories\UserRepository;
use Illuminate\View\View;

class PlanController extends Controller
{
    public PlanService $planService;
    public UserRepository $userRepository;

    public function __construct(
        PlanService $planService,
        UserRepository $userRepository
    )
    {
        $this->planService = $planService;
        $this->userRepository = $userRepository;
    }

    /**
     * Show the application plan
     * @return View
     */
    public function showPlan():View
    {
        $userId = "c0cf400b-b81f-4779-9a1d-12ae3978ac3a";
        $listPlan = $this->planService->getPlans($userId)->toArray();
        return view('front-end.layouts.layout_plan');
    }

    /**
     * @return View
     */
    public function formCreatePlan(): View
    {
        $userId = "c0cf400b-b81f-4779-9a1d-12ae3978ac3a";
        $listUser = $this->userRepository->find()->toArray();
        return view('front-end.layouts.layout_create_plan', compact('listUser', "userId"));
    }

    /**
     * @return true
     */
    public function createPlan(Request $request): bool
    {
        $plan = $this->planService->createPlan($request->only(Plan::NAME, Plan::DESCRIPTION, Plan::DESCRIPTION));
        $this->planService->createPlanMember($request->only('list_member'), $plan);
        return true;
    }

}
