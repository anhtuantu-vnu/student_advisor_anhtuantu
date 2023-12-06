<?php

namespace App\Imports;
use App\Models\ClassRole;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Services\FileServices;
use Maatwebsite\Excel\Concerns\SkipsErrors;
class StudentImport implements ToModel, WithChunkReading, WithHeadingRow, SkipsEmptyRows, SkipsOnError
{
    use Importable, SkipsErrors;

    /**
     * @param array $row
     * @throws BindingResolutionException
     */
    public function model(array $row)
    {
        $fileServer = app()->make(FileServices::class);
        $data = $fileServer->importFileStudent($row);

        if(empty($data['user'])) return;

        return new ClassRole([
            'uuid' => Str::uuid(),
            'user_id' => $data['user']['uuid'],
            'created_at' => Carbon::today(),
            'updated_at' => Carbon::today(),
            'class_id' => $data['class']['uuid'],
            'role' => User::ROLE_STUDENT
        ]);
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
