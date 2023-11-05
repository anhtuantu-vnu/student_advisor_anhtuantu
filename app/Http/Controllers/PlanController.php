<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Services\PlanService;
use App\Repositories\UserRepository;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

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
        $listPlanGroup = collect($dataPlan['list_plan'])->countBy('status_key');
        foreach ($listPlanGroup as $key => $value) {
            $dataPlan['data'][$key] = $value;
        }
        $dataPlan['data']['total_plan'] = $listPlanGroup->sum();
        return view('front-end.layouts.plan.layout_plan', compact('dataPlan'));
    }

    /**
     * @return View
     */
    public function formCreatePlan(): View
    {
        return view('front-end.layouts.plan.layout_create_plan');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getListMember(Request $request): JsonResponse
    {
        $data = $this->userRepository->searchMemberByCondition($request->input('search'));
        return $this->successWithContent($data);
    }

    /**
     * @param Request $request
     * @return Application|\Illuminate\Foundation\Application|RedirectResponse|Redirector
     */
    public function createPlan(Request $request): \Illuminate\Foundation\Application|Redirector|RedirectResponse|Application
    {
        $plan = $this->planService->createPlan($request->only('name' , 'description'), Auth::user()->uuid);
        if($request->input('list_member')) {
            $this->planService->createPlanMember($request->only('list_member'), $plan);
        }
        return redirect("/to-do?id=".$plan['uuid']);
    }
}
