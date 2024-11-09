<?php

namespace CityChronicles\Text;

class CommandText
{
    public const START_BOT = "/старт";

    public const INFO_CITY = "/инфо_город";
    public const INFO_GOD = "/инфо_бог";
    public const INFO_PLAYER = "/инфо_персонаж";

    public const DAILY_EVENT = "/событие";
    public const SEND_EVENT_GOOD = "/послать_удачу";
    public const SEND_EVENT_EVIL = "/послать_неудачу";

    public const HELP = "/помощь";

    public const STATISTIC_ALL = "/статистика";

    public const OTHER_COMMAND = "/другая команда";

    // addictional commands
    public const FIND_MONEY = "/искать_деньги";
    public const DRINK_BEER = "/выпить";
    

    public static function getAllCommand()
    {

        $help = "Список всех команд:\n";

        $start = CommandText::START_BOT . " - запуск бота. Эта команда выполняется в самом начале и в дальнейшем не используется\n\n";
        $infoCity = CommandText::INFO_CITY . " - получить информацию о городе (название, текущий ранг, богатство города, жители города, скорость времени, сколько прожито дней)\n\n";
        $infoCharacter =  CommandText::INFO_PLAYER . " - информация о своем себе (имя жителя, фамилия жителя, возраст, нынешнее здоровье, количество денег, профессия)\n\n";
        $event = CommandText::DAILY_EVENT . " - получить событие которое произошло в городе. Можно запускать каждые 8 часов\n\n";
        $sendGood = CommandText::SEND_EVENT_GOOD . " - послать удачу. Каждый участник беседы имеет право за один день послать удачу или неудачу в город и узнать что случилось\n\n";
        $sendEvil = CommandText::SEND_EVENT_EVIL . " - послать неудачу\n\n";
        $statistics =  CommandText::STATISTIC_ALL . " - статистика по беседе (топ везунчиков и неудачников за все время)\n\n";

        return $help . $start . $event . $sendGood . $sendEvil . $statistics . $infoCity . $infoCharacter;
    }
    public static function getHelloCommand()
    {
        $event = "Каждый день используйте команду: " . CommandText::DAILY_EVENT . " - эта команда получает событие которое произошло в городе. Можно запускать каждые 8 часов\n\n";

        $end = "Для просмотра всех команд используйте: " . CommandText::HELP . "\n\n";

        return $event . $end;
    }
}
