<?php

namespace CityChronicles\Text;

class EventText
{

    private static function getInfoGod(array $charactersID, array $gods)
    {
        $text = "";
        foreach ($gods as $god) {
            if (isset($charactersID[$god->getId()])) {
                $text .=  $god->getName() . " " . $god->getLastName() . ", ";
            }
        }

        $text = substr($text, 0, strlen($text) - 2);

        return $text;
    }

    public static function getGoodText(array $charactersID, array $gods, $isAll): string
    {

        $text = $isAll ? "Удача 👍 (для всех)\n***\n" : "Удача 👍 (" . self::getInfoGod($charactersID, $gods) . ")\n***\n";

        return $text;
    }
    public static function getEvilText(array $charactersID, array $gods, $isAll): string
    {
        $text = $isAll ? "Неудача 👎 (для всех)\n***\n" : "Неудача 👎 (" . self::getInfoGod($charactersID, $gods) . ")\n***\n";

        return $text;
    }

    public static function getNeutralText(array $charactersID, array $gods, $isAll): string
    {
        $text = $isAll ? "Нейтральное событие 🤝 (для всех)\n***\n" : "Нейтральное событие 🤝 (" . self::getInfoGod($charactersID, $gods) . ")\n***\n";
        return $text;
    }
}
