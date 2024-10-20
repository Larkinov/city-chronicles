<?php

namespace vkbot_conversation\utils;

class Converter
{
    static function arrayToObject(array $data): mixed
    {
        $objectData = new \stdClass();
        foreach ($data as $key => $value) {
            $objectData->$key = $value;
        }
        return $objectData;
    }
}
