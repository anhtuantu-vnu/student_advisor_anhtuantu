<?php

namespace App\Http\Controllers;

use App\Models\PlanMember;
use App\Repositories\PlanMemberRepository;
use App\Repositories\PlanRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Services\PlanService;
use App\Repositories\UserRepository;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
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
     * @return View
     */
    public function showPlanUpdate(): View
    {
        return view('front-end.layouts.plan.update_plan');
    }

    /**
     * @param Request $request
     * @return Application|\Illuminate\Foundation\Application|RedirectResponse|Redirector
     */
    public function acceptInvitePlan(Request $request): \Illuminate\Foundation\Application|Redirector|RedirectResponse|Application
    {
        $data = $this->planMemberRepository->updateOrCreate(['uuid' => $request->input('plan_member')], ['status_invite' => PlanMember::STATUS_ACCEPT_PLAN]);
        return redirect('/to-do?id='. $data['plan_id']);
    }

    /**
     * @return JsonResponse
     */
    public function getPlanLimit(): JsonResponse
    {
        $listPlan = [];
        $this->planMemberRepository->getListPlanByMemberLimit(Auth::user()->uuid, 3)->sortByDesc('updated_at')->each(function($plan) use(&$listPlan){
            $dataReturn = $plan['planByMemberId'];
            $date = Carbon::createFromFormat('Y-m-d H:i:s', $dataReturn['updated_at']);
            $dataReturn['updated_at_fomat'] = $date->format('F d, Y');
            $listPlan[] = $dataReturn;
        });
        return $this->successWithContent($listPlan);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getDataPlanUpdate(Request $request): JsonResponse
    {
        $id = $request->input('id');
        $plan = $this->planRepository->findOne(['uuid' => $id])->toArray();
        $plan['listMember'] = $this->planMemberRepository->getMemberByPlanId($id);
        return $this->successWithContent($plan);
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
     * @param Request $request
     */
    public function updateDataPlan(Request $request)
    {
        return $this->planService->updateDataPlan($request->input());
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
        $data = $this->userRepository->searchMemberByCondition($request->only('search', 'member_selected'));
        return $this->successWithContent($data);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function createPlan(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $plan = $this->planService->createPlan($request->only('name' , 'description'), Auth::user()->uuid);
            if($request->input('list_member')) {
                $this->planService->createPlanMember($request->only('list_member'), $plan);
            }
            DB::commit();
            return $this->successWithContent(['link_redirect' => "/to-do?id=".$plan['uuid']], 'Create Success');
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->failedWithErrors(500, $th->getMessage());
        }
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function deletePlan($id): JsonResponse
    {
        return $this->planService->deleteDataPlan($id);
    }
}
