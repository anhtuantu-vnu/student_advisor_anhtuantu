<?php
use Carbon\Carbon;

if (! function_exists('format_time_import')) {
    /**
     * @param $time
     * @param $type
     * @return string
     */
    function format_time_import($time, $type): string
    {
        return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($time)->format('Y-m-d');
    }
}

if (! function_exists('count_week')) {
    /**
     * @param $startTime
     * @param $endTime
     * @return string
     */
    function count_week($startTime, $endTime): string
    {
        $start = Carbon::parse($startTime);
        $end = Carbon::parse($endTime);
        return $start->diffInWeeks($end);
    }
}
