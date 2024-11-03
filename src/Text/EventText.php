<?php

namespace CityChronicles\Text;

class EventText
{

    private static function getNamePlace(string $place): string
    {
        $namePlace = '';
        switch ($place) {
            case "animal":
                $namePlace = "В мире животных";
                break;
            case "learn":
                $namePlace = "Гранит науки";
                break;
            case "transport":
                $namePlace = "Дорожные войны";
                break;
            case "travel":
                $namePlace = "Время путешествий";
                break;
            case "astrology":
                $namePlace = "Ретроградный Меркурий";
                break;
        }
        return $namePlace;
    }

    private static function getNamesGods(array $charactersID, array $gods)
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

    public static function getGoodText(array $charactersID, array $gods, bool $isAll, string $place): string
    {
        if ($place !== "0") {
            $place = self::getNamePlace($place);
            $text = $isAll ? "Удача - для всех 🍀\n***\n" : "Удача - " . self::getNamesGods($charactersID, $gods) . " 🍀 (Тема - $place)\n***\n";
        } else
            $text = $isAll ? "Удача - для всех 🍀\n***\n" : "Удача - " . self::getNamesGods($charactersID, $gods) . " 🍀\n***\n";
        return $text;
    }
    public static function getEvilText(array $charactersID, array $gods, bool $isAll, string $place): string
    {
        if ($place !== "0") {
            $place = self::getNamePlace($place);
            $text = $isAll ? "Неудача - для всех 🤡\n***\n" : "Неудача - " . self::getNamesGods($charactersID, $gods) . " 🤡 (Тема - $place)\n***\n";
        } else
            $text = $isAll ? "Неудача - для всех 🤡\n***\n" : "Неудача - " . self::getNamesGods($charactersID, $gods) . " 🤡\n***\n";

        return $text;
    }

    public static function getNeutralText(array $charactersID, array $gods, bool $isAll): string
    {
        $text = $isAll ? "Нейтральное событие - для всех 📌\n***\n" : "Нейтральное событие - " . self::getNamesGods($charactersID, $gods) . " 📌\n***\n";
        return $text;
    }
}
