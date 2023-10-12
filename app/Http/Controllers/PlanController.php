<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;
use App\Services\PlanService;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Carbon\Carbon;

class PlanController extends Controller
{
    /**
     * @var PlanService
     */
    protected PlanService $planService;
    /**
     * @var UserRepository
     */
    protected UserRepository $userRepository;

    /**
     * @param PlanService $planService
     * @param UserRepository $userRepository
     */
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
        $dataPlan['list_plan'] = $this->planService->getPlans(Auth::user()->uuid);
        $groupByStatus = collect($dataPlan['list_plan'])->countBy('status_key')->toArray();
        foreach ($groupByStatus as $key => $value) {
            $dataPlan['data'][$key] = $value;
        }
        dd($dataPlan);
        return view('front-end.layouts.layout_plan', compact('dataPlan'));
    }

    /**
     * @return View
     */
    public function formCreatePlan(): View
    {
        $listUser = $this->userRepository->find();
        return view('front-end.layouts.layout_create_plan', compact('listUser'));
    }

    /**
     * @return true
     */
    public function createPlan(Request $request): bool
    {
        $plan = $this->planService->createPlan($request->only('name' , 'description'), Auth::user()->uuid);
        $this->planService->createPlanMember($request->only('list_member'), $plan);
        return true;
    }

}
