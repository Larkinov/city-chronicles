<?php

namespace CityChronicles\Text;


class TextCharacter
{
    private const NAME_BOT = ["Славян", "Вован", "Баир"];
    private const LAST_NAME_BOT = ["Большой", "Сутулый", "Первородный"];

    public const HEALTH = ["здоровый", "больной"];
    public const PROFESSION_NO = "безработный";
    public const PROFESSION_1 = "охранник дупла";
    public const PROFESSION_2 = "сторожевой пес";
    public const PROFESSION_3 = "кухарка лесная";
    public const PROFESSION_4 = "ловец глистов";

    public static function getRandomName(): string
    {
        return self::NAME_BOT[array_rand(self::NAME_BOT)];
    }
    public static function getRandomLastName(): string
    {
        return self::LAST_NAME_BOT[array_rand(self::LAST_NAME_BOT)];
    }

    public static function getRandomHealth(): string
    {
        return self::HEALTH[array_rand(self::HEALTH)];
    }
    public static function getRandomProfession(): string
    {
        $arr = [self::PROFESSION_1, self::PROFESSION_NO, self::PROFESSION_2, self::PROFESSION_3, self::PROFESSION_4];
        return $arr[array_rand($arr)];
    }

    public static function getRandomAge(): int
    {
        return rand(10, 60);
    }

    public static function getPositionText(array $characters, bool $isLucky): string
    {

        $message = $isLucky ? "📅 Статистика везунчиков за все время:\n" : "📅 Статистика неудачников за все время:\n";

        foreach ($characters as $key => $value) {

            $lucky = $isLucky ? $value->getLuckyCount() : $value->getUnluckyCount();
            $firstName = $value->getName();
            $lastName = $value->getLastName();
            switch ($key) {
                case 0:
                    $message .= "🥇 $firstName $lastName - $lucky\n";
                    break;
                case 1:
                    $message .= "🥈 $firstName $lastName - $lucky\n";
                    break;
                case 2:
                    $message .= "🥉 $firstName $lastName - $lucky\n";
                    break;
                default:
                    $message .= "$firstName $lastName - $lucky\n";
            }
        }

        return $message;
    }
}
