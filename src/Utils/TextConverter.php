<?php

namespace CityChronicles\Utils;

class TextConverter
{
    public static function convertTextGenderCommaFullName(string $text, int $isMan, string $fullname)
    {
        $newText = preg_replace_callback('/(\S+)\/(\S+)/', function ($matches) use ($isMan) {
            return $isMan === 1 ? $matches[1] : $matches[2];
        }, $text);

        $newText = str_replace("~", ",", $newText);

        $newText = str_replace("CHARACTER_1", $fullname, $newText);

        return $newText;
    }
}
