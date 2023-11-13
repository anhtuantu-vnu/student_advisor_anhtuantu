<?php

namespace App\Imports;
use Carbon\Carbon;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\Class_;
use App\Services\FileServices;
class StudentScheduleImport implements ToModel, WithChunkReading, WithHeadingRow
{
    use Importable;

    /**
     * @param array $row
     *
     * @return bool
     * @throws BindingResolutionException
     */
    public function model(array $row): bool
    {
        dd($row);
       $fileServer = app()->make(FileServices::class);
       $fileServer->importFileStudent($row);
//        $class = $this->checkClassInFile($row);
//        dd($class, 123);
        return true;
    }

//    private function checkClassInFile($data) {
//        $dataFind = [
//            'name' => $data['class'],
//            'code' => $data['code_class']
//        ];
//        $class = Class_::where($dataFind)->first();
//        if (empty($class)) {
//            $dataFind['uuid'] = Str::uuid();
//            $dataFind['department_id'] = Str::uuid();
//            $dataFind['created_at'] = Carbon::today();
//            $dataFind['updated_at'] = Carbon::today();
//            $dataFind['start_year'] = $data['start_year'];
//            $dataFind['end_year'] = $data['end_year'];
//            $class = Class_::create($dataFind);
//        }
//        return $class;
//    }

    public function chunkSize(): int
    {
        return 100;
    }
}
