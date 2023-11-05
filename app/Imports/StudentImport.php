<?php

namespace App\Imports;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Services\FileServices;
class StudentImport implements ToModel, WithChunkReading, ShouldQueue, WithHeadingRow
{
    use Importable;
    /**
     * @param array $row
     *
     */
    public function model(array $row)
    {
        $fileServer = app()->make(FileServices::class);
        $fileServer->importFileStudent($row);
        return true;
    }

    public function chunkSize(): int
    {
        return 100;
    }
}
