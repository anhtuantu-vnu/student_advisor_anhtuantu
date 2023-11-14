<?php

namespace App\Repositories;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserRepository extends AbstractRepository
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    /**
     * @param $data
     * @return mixed
     */
    public function searchMemberByCondition($data): mixed
    {
        $search = $data['search'];
        $dataReturn = $this->model->where(function($query) use ($search) {
            $query->orWhere('email', 'LIKE' , "%$search%")
                ->orWhere('last_name', 'LIKE' , "%$search%")
                ->orWhere('first_name', 'LIKE' , "%$search%");
        })->where('uuid' , '<>', Auth::user()->uuid);
        if(count(json_decode($data['member_selected'], true))) {
            $listIdMemberSelected = collect(json_decode($data['member_selected'], true))->map(function($member) {
                return $member['id'];
            })->toArray();
            $dataReturn = $dataReturn->whereNotIn('id', $listIdMemberSelected);
        }
        return $dataReturn->get();
    }
}
