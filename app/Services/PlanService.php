<?php
namespace App\Services;
use App\Repositories\PlanRepository;
use Carbon\Carbon;
use Illuminate\Support\Str;

class PlanService
{
    /**
     * @var PlanRepository
     */
    protected PlanRepository $planRepository;


    /**
     * @param PlanRepository $planRepository
     */
    public function __construct(
        PlanRepository $planRepository
    )
    {
        $this->planRepository = $planRepository;
    }

    /**
     * @param $plan
     * @return PlanRepository
     */
    public function createPlan($plan): mixed
    {
        $plan['uuid'] = Str::uuid();
        $plan['created_at'] = Carbon::today();
        $plan['updated_at'] = Carbon::today();
        return $this->planRepository->create($plan);
    }

}
