<?php

if (! function_exists('format_time_import')) {
    /**
     * @param $time
     * @return string
     */
    function format_time_import($time): string
    {
        return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($time)->format('Y-m-d');
    }
}
