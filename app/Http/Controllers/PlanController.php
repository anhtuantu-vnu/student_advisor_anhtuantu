<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Repositories\PlanMemberRepository;
use App\Repositories\PlanRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Services\PlanService;
use App\Repositories\UserRepository;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
     * @var PlanRepository
     */
    protected PlanRepository $planRepository;
    /**
     * @var PlanMemberRepository
     */
    protected PlanMemberRepository $planMemberRepository;

    /**
     * @param PlanService $planService
     * @param UserRepository $userRepository
     * @param PlanRepository $planRepository
     * @param PlanMemberRepository $planMemberRepository
     */
    public function __construct(
        PlanService $planService,
        UserRepository $userRepository,
        PlanRepository $planRepository,
        PlanMemberRepository $planMemberRepository
    )
    {
        $this->planService = $planService;
        $this->userRepository = $userRepository;
        $this->planRepository = $planRepository;
        $this->planMemberRepository = $planMemberRepository;
    }

    /**
     * Show the application plan
     * @return View
     */
    public function showPlan():View
    {
        return view('front-end.layouts.plan.layout_plan');
    }

    /**
     * @return Application|Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
    public function showPlanUpdate($id): \Illuminate\Foundation\Application|\Illuminate\Contracts\View\View|Factory|Application
    {
        $plan = $this->planRepository->findOne(['uuid' => $id])->toArray();
        $plan['listMember'] = $this->planMemberRepository->getMemberByPlanId($id);
        return view('front-end.layouts.plan.update_plan', compact('plan'));
    }
    /**
     * @return JsonResponse
     */
    public function getDataPlan(): JsonResponse
    {
        $dataPlan['list_plan'] = $this->planService->getPlans(Auth::user()->uuid);
        $listPlanGroup = collect($dataPlan['list_plan'])->countBy('status_key');
        foreach ($listPlanGroup as $key => $value) {
            $dataPlan['data'][$key] = $value;
        }
        $dataPlan['data']['total_plan'] = $listPlanGroup->sum();
        return $this->successWithContent($dataPlan);
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

    /**
     * @param $id
     * @return JsonResponse
     */
    public function deletePlan($id): JsonResponse
    {
        try {
            DB::beginTransaction();
//            $this->planRepository->deleteByCondition(['uuid' => $id]);
            DB::commit();
            return $this->successWithNoContent('Delete Success');
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->failedWithErrors(500, $th->getMessage());
        }
    }
}
