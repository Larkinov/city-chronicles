<?php

namespace CityChronicles\Settings;

use CityChronicles\City\City;

class Settings
{

    public const ALREADY_START = "Бот уже запущен!";
    public const NEED_START = "Необходимо сначала запустить бота!";
    public const WAITING_TIME = "Время ожидания еще не прошло";
    public const WAITING_TIME_CHARACTER = "Ваше время ожидания еще не прошло";
    public const ANOTHER_COMMAND_TEXT = "Такой команды не существует. Для просмотра команд используйте /помощь";


    //время ожидания (зависит от множителя) для следующего события или отправки удачи/неудачи
    public const WAITING_EVENT_HOURS_TIME = 23;
    public const WAITING_GOOD_EVENT_HOURS_TIME = 23;
    public const WAITING_EVIL_EVENT_HOURS_TIME = 23;

    //множитель подсчета прошедшего времени: 1 - считать разницу в секундах, 3600 - считать разницу в часах
    public const CITY_WAITING_MULTIPLIER = 3600;


    private static function getTextHour(int $timeout): string
    {
        $textHour = match (true) {
            $timeout <= 24 && $timeout >= 22, $timeout <= 4 && $timeout >= 2  => "часа",
            $timeout === 1, $timeout === 21 => "час",
            $timeout >= 5 && $timeout <= 20 => "часов",
            $timeout === 0 => "меньше часа",
            default => "час",
        };

        return $textHour;
    }

    //настройка выпадения случайных событий
    public static function getRandomTypeEvent()
    {
        //33% - evil
        //33% - goodness
        //33% - neutral
        // $rand = random_int(1, 100);
        // if ($rand >= 1 && $rand <= 30)
        //     return -1;
        // elseif ($rand > 30 && $rand <= 60)
        //     return 1;
        // elseif ($rand > 60)
        //     return 0;

        //40% - evil
        //40% - goodness
        //10% - neutral
        $rand = random_int(1, 100);
        if ($rand >= 1 && $rand <= 39)
            return -1;
        elseif ($rand > 39 && $rand <= 80)
            return 1;
        elseif ($rand > 80)
            return 0;
    }

    public static function getWaitingTimeEvent(City $city): string
    {
        $timeout = abs($city->getElapsedHoursEvent() - intval(self::WAITING_EVENT_HOURS_TIME));
        $textHour = self::getTextHour($timeout);
        return self::WAITING_TIME . ", до следующего события осталось - $timeout $textHour ⌚";
    }


    public static function getWaitingTimeInfluentialEvent(int $time): string
    {
        $timeout = abs($time - intval(self::WAITING_EVENT_HOURS_TIME));
        $textHour = self::getTextHour($timeout);
        return self::WAITING_TIME . ", до следующей отправки осталось - $timeout $textHour ⌚";
    }
}
