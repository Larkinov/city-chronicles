<?php

namespace CityChronicles\Text;

use CityChronicles\City\City;
use CityChronicles\Text\CommandText;

class TextCity
{

    public const RANK_1 = "город";
    public const RANK_2 = "столица";
    public const NAME_RANK_1 = ['Дарагановка', 'Прогресс ничтожный', 'Старая станица', 'Шунтукские бомжи', 'Пролетарское днище', 'Кружилинские беды', 'Игнатьевские штучки', 'Гуамка нищенская', 'Челобитьево', 'Усть-Кишертъ', 'Шамонино', 'Лопухи', 'Большая ржака', 'Колхоз новые', 'Да-да', 'Cтарокозьмодемьяновское', 'Тупица', 'Горшки казенные', 'Воробьи', 'Веселая жизнь', 'Упоровка', 'Подмой', 'Песочня', 'Горчуха необыкновенная', 'Лашма', 'Екимовичи', 'Уварово', 'Новый Некоуз', 'Анциферово', 'Чапаево', 'Мяунджа', 'Сеймчан', 'Белоярский', 'Кирицы',  'Зебляки',  'Карабаш', 'Лянтор', 'Тарко-Сале', 'Тутончаны', 'Хочо', 'Глухариный', 'Весенний'];
    public const NAME_RANK_2 = ['Вена', 'Берлин', 'Москва', 'Вашингтон', 'Мехико', 'Рио-Де-Жанейро', 'Осло', 'Сидней', 'Дижон', 'Ренн', 'Ла-Корунья', 'Баринас', 'Рапид-Сити', 'Валемаунт', 'Драммен', 'Лион', 'Марракеш', 'Аккра', 'Сан-Паулу', 'Даллас', 'Осака'];

    public const SPEED_TIME_0 = "время остановлено";
    public const SPEED_TIME_1 = "обычное";
    public const SPEED_TIME_2 = "ускоренно в два раза";
    public const SPEED_TIME_3 = "ускоренно в три раза";
    public const SPEED_TIME_4 = "ускоренно в четыре раза";

    private const ADD_NEW_PROFILE_RANK_1 = ['Кое-кто выиграл путевку в наш город, но обратный билет ему/ей не дали. Поэтому пришлось остаться... Добро пожаловать - CHARACTER_1!', 'Мимо пролетал самолет. Оттуда свалился незнакомец/незнакомка и как ни в чем не бывало начал/начала жить в нашем городе... Добро пожаловать - CHARACTER_1'];
    private const ADD_NEW_PROFILE_RANK_2 = ['Из леса прибежал/прибежала бомжеватый/бомжеватая на вид незнакомец/незнакомка. Приятно познакомиться, CHARACTER_1!', 'Мимо пролетал вертолет. Оттуда свалился новый член группы - CHARACTER_1'];

    private const HELLO_RANK_1 = "Мы открыли в вашей беседе город 🏢 и поселили туда ваших персонажей, они сразу же дали ему название!\n\nДобро пожаловать в ваш новый город - CITY_NAME!\n\nЖители города: ";
    private const HELLO_RANK_2 = "Спустя такое большое количество произошедших событий, было решено сделать из города столицу. \n\nВаше поселение получило новый ранг - Столица!\n\nПоэтому поводу было придумано новое название, добро пожаловать в столицу - CITY_NAME!\n\nЖители столицы: ";


    public static function getName(string $rank)
    {
        $name = match (true) {
            $rank === self::RANK_1  => self::getRandomName(self::NAME_RANK_1),
            $rank === self::RANK_2  => self::getRandomName(self::NAME_RANK_2),
            default => self::getRandomName(self::NAME_RANK_1),
        };
        return $name;
    }

    private static function getRandomName(array $namesRank): string
    {
        $random = rand(0, count($namesRank) - 1);
        return $namesRank[$random];
    }

    public static function getRank(int $rich): string
    {
        $rank = match (true) {
            $rich >= 0 && $rich <= 1000 => self::RANK_1,
            $rich >= 1001 => self::RANK_2,
            default => self::RANK_1,
        };

        return $rank;
    }

    public static function addNewProfile(string $rank)
    {
        $message = match (true) {
            $rank === self::RANK_1  => self::ADD_NEW_PROFILE_RANK_1[array_rand(self::ADD_NEW_PROFILE_RANK_1)],
            $rank === self::RANK_2  => self::ADD_NEW_PROFILE_RANK_2[array_rand(self::ADD_NEW_PROFILE_RANK_2)],
            default =>  self::ADD_NEW_PROFILE_RANK_1[array_rand(self::ADD_NEW_PROFILE_RANK_1)],
        };

        return $message;
    }

    public static function getHelloText(City $city): string
    {
        $rank = $city->getRank();
        $message = match (true) {
            $rank === self::RANK_1  => TextCity::HELLO_RANK_1,
            $rank === self::RANK_2  => TextCity::HELLO_RANK_2,
            default =>  TextCity::HELLO_RANK_1,
        };

        $message = str_replace("CITY_NAME", $city->getName(), $message);

        $people = "";
        foreach ($city->getAllCharacter() as $value) {
            $people .= $value->getName() . " " . $value->getLastName() . ", ";
        }

        $people = substr($people, 0, strlen($people) - 2);

        $message .= $people;

        $message .= "\n\n" . CommandText::getHelloCommand();
        return $message;
    }
}
