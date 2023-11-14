<?php

namespace App\Repositories;

use App\Models\Class_;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ClassRepository extends AbstractRepository
{
    public function __construct(Class_ $model)
    {
        parent::__construct($model);
    }

    /**
     * @param $data
     */
    public function checkClassInFileUpload($data)
    {
        $dataFind = [
            'name' => $data['class'],
            'code' => $data['code_class']
        ];
        $class = $this->findOne($dataFind);
        if (empty($class)) {
            $dataFind['uuid'] = Str::uuid();
            $dataFind['department_id'] = Str::uuid();
            $dataFind['created_at'] = Carbon::today();
            $dataFind['updated_at'] = Carbon::today();
            $dataFind['start_year'] = $data['start_year'];
            $dataFind['end_year'] = $data['end_year'];
            $class = $this->create($dataFind);
        }
        return $class;
    }
}
