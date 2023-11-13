<?php

namespace App\Imports;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\Intake;
use App\Models\IntakeMember;
use App\Models\Subject;
use Carbon\Carbon;
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
        $subject = Subject::where('code' ,$row['code_class'])->first();
        $now = Carbon::now();
        echo json_encode([
            'uuid' => Str::uuid(),
            'code' => sprintf('%s%u%u', $row['code_class'], $now->month, $now->year),
            'subject_id' => $subject['uuid'],
            'start_date' => format_time_import($row['start_date']),
            'end_date' => format_time_import($row['end_date']),
            'duration_weeks' => 3,
            'updated_at' => 2434,
            'created_at' => 123123,
            'start_hour' => 123123,
            'start_minute' => 123,
            'end_hours' => 123,
            'week_days' => $row['week_day'],
            'location' => $row['location']
        ]);
        dd($subject, $row);
        $intake = Intake::create([
            'uuid' => Str::uuid(),
            'code' => sprintf('%E%u%u', $row['code_class'], $now->month, $now->year),
            'subject_id' => $subject['uuid'],
            'start_date' => format_time_import($row['start_date']),
            'end_date' => 23123,
            'duration_weeks' => 3,
            'updated_at' => 2434,
            'created_at' => 123123,
            'start_hour' => 123123,
            'start_minute' => 123,
            'end_hours' => 123,
            'week_days' => $row['week_day'],
            'location' => $row['location']
        ]);
//        $class = $this->checkClassInFile($row);
//        dd($class, 123);
        return true;
    }

    private function convertTime() {

    }

    public function chunkSize(): int
    {
        return 100;
    }
}
