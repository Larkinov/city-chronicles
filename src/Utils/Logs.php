<?php

namespace CityChronicles\Utils;

class Logs
{

    private const LOG_STORAGE = __DIR__ . "/../../logs";

    public const FULL_LOG = "full_log";

    public static function writeLog(string $filename, string $log)
    {

        $fulldata = date("d-m-Y h:i:s");
        $data = date("d-m-Y");

        file_put_contents(self::LOG_STORAGE . "/$filename" . "_$data", "$fulldata: $log\n", FILE_APPEND);
    }
}