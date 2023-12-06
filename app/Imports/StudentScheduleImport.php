<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\Intake;
use App\Models\IntakeMember;
use App\Models\Subject;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StudentScheduleImport implements ToModel, WithChunkReading, WithHeadingRow, SkipsEmptyRows
{
    use Importable;

    /**
     * @param array $row
     *
     * @throws BindingResolutionException
     */
    public function model(array $row)
    {
        try {
            DB::beginTransaction();

            $subject = Subject::where('code', $row['code_class'])->first();
            $user = User::where('email', $row['student_email'])->first();
            $now = Carbon::now();
            $startDate = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['start_date'])->format('Y-m-d');
            $endDate = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['end_date'])->format('Y-m-d');
            $intake = Intake::create([
                'uuid' => Str::uuid(),
                'code' => sprintf('%s-%u%u', $row['code_class'], $now->month, $now->year),
                'subject_id' => $subject->uuid,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'duration_weeks' => count_week($startDate, $endDate),
                'updated_at' => Carbon::today(),
                'created_at' => Carbon::today(),
                'start_hour' => $row['start_hour'],
                'start_minute' => $row['start_minute'],
                'end_hours' => $row['end_hour'],
                'end_minute' => $row['end_minute'],
                'week_days' => $row['week_day'],
                'location' => $row['location']
            ]);
            DB::commit();

            return new IntakeMember([
                'uuid' => Str::uuid(),
                'updated_at' => Carbon::today(),
                'created_at' => Carbon::today(),
                'attendance_points' => $row['attendance_points'],
                'mid_term_points' => $row['mid_term_points'],
                'last_term_points' => $row['last_term_points'],
                'user_id'  => $user->uuid,
                'role' => User::ROLE_STUDENT,
                'intake_id' => $intake->uuid
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
        }
    }

    public function rules(): array
    {
        return [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required',
            'password' => 'required',
            'gender' => 'required',
            'date_of_birth' => 'required'
        ];
    }

    public function chunkSize(): int
    {
        return 50;
    }
}
