<?php

namespace CityChronicles\Text;

use CityChronicles\City\City;
use CityChronicles\Text\CommandText;

class TextCity
{

    public const RANK_1 = "лагерь";
    public const RANK_2 = "деревня";
    public const RANK_3 = "поселок городского типа";
    public const RANK_4 = "город";
    public const RANK_5 = "столица";
    public const NAME_RANK_1 = ["Одноногий путник", "Мокрые и злые", "Кормящие комаров", "Выживалово", "Голодуха", "Бомжатник", "Ктокудашники", "Униженные и оскорбленные", "Бойскауты по неволе", 'Гроза медведей', 'Продленка', 'Вонючие носки', 'Мухоморы', 'Тараканьи бега'];
    public const NAME_RANK_2 = ['Дарагановка', 'Прогресс ничтожный', 'Старая станица', 'Шунтукские бомжи', 'Пролетарское днище', 'Кружилинские беды', 'Игнатьевские штучки', 'Гуамка нищенская', 'Челобитьево', 'Усть-Кишертъ', 'Шамонино', 'Лопухи', 'Большая ржака', 'Колхоз новые', 'Да-да', 'Cтарокозьмодемьяновское', 'Тупица', 'Горшки казенные', 'Воробьи', 'Веселая жизнь', 'Упоровка', 'Подмой'];
    public const NAME_RANK_3 = ['Песочня', 'Горчуха необыкновенная', 'Лашма', 'Екимовичи', 'Уварово', 'Новый Некоуз', 'Анциферово', 'Чапаево', 'Мяунджа', 'Сеймчан', 'Белоярский', 'Кирицы',  'Зебляки',  'Карабаш', 'Лянтор', 'Тарко-Сале', 'Тутончаны', 'Хочо', 'Глухариный', 'Весенний'];
    public const NAME_RANK_4 = ['Дижон', 'Ренн', 'Ла-Корунья', 'Баринас', 'Рапид-Сити', 'Валемаунт', 'Драммен', 'Лион', 'Марракеш', 'Аккра', 'Сан-Паулу', 'Даллас', 'Осака'];
    public const NAME_RANK_5 = ['Вена', 'Берлин', 'Москва', 'Вашингтон', 'Мехико', 'Рио-Де-Жанейро', 'Осло', 'Сидней'];

    public const SPEED_TIME_0 = "время остановлено";
    public const SPEED_TIME_1 = "обычное";
    public const SPEED_TIME_2 = "ускоренно в два раза";
    public const SPEED_TIME_3 = "ускоренно в три раза";
    public const SPEED_TIME_4 = "ускоренно в четыре раза";

    private const ADD_NEW_PROFILE_RANK_1 = ['Из леса прибежал/прибежала бомжеватый/бомжеватая на вид незнакомец/незнакомка. Приятно познакомиться, CHARACTER_1!', 'Мимо пролетал вертолет. Оттуда свалился новый член группы - CHARACTER_1'];
    private const ADD_NEW_PROFILE_RANK_2 = ['Из леса прибежал/прибежала бомжеватый/бомжеватая на вид незнакомец/незнакомка. Приятно познакомиться, CHARACTER_1!', 'Мимо пролетал вертолет. Оттуда свалился новый член группы - CHARACTER_1'];
    private const ADD_NEW_PROFILE_RANK_3 = ['Из леса прибежал/прибежала бомжеватый/бомжеватая на вид незнакомец/незнакомка. Приятно познакомиться, CHARACTER_1!', 'Мимо пролетал вертолет. Оттуда свалился новый член группы - CHARACTER_1'];
    private const ADD_NEW_PROFILE_RANK_4 = ['Из леса прибежал/прибежала бомжеватый/бомжеватая на вид незнакомец/незнакомка. Приятно познакомиться, CHARACTER_1!', 'Мимо пролетал вертолет. Оттуда свалился новый член группы - CHARACTER_1'];
    private const ADD_NEW_PROFILE_RANK_5 = ['Из леса прибежал/прибежала бомжеватый/бомжеватая на вид незнакомец/незнакомка. Приятно познакомиться, CHARACTER_1!', 'Мимо пролетал вертолет. Оттуда свалился новый член группы - CHARACTER_1'];
    
    private const HELLO_RANK_1 = "После долгих утомительных странствий ваша группа, наконец, решает построить здесь лагерь - поставить палатки, развести огонь, добыть еды...\n\nДобро пожаловать в лагерь - CITY_NAME!\n\nЖители лагеря: ";

    private const HELLO_RANK_2 = "Мало-помалу слава о вашем лагере пронеслась по всей лесной округе. Охотники, рыбаки, путешественники и случайные незнакомцы - каждый из них рассказывал об этом удивительном лагере и его непростых жителях. Постепенно в лагере стали поселяться новые люди и вскоре толпы незнакомых людей обустроили настоящую деревню. \n\nВаше поселение получило новый ранг - деревня!\n\nДобро пожаловать в деревню - CITY_NAME!\n\nОснователи деревни: ";


    public static function getName(string $rank)
    {
        $name = match (true) {
            $rank === self::RANK_1  => self::getRandomName(self::NAME_RANK_1),
            $rank === self::RANK_2  => self::getRandomName(self::NAME_RANK_2),
            $rank === self::RANK_3  => self::getRandomName(self::NAME_RANK_3),
            $rank === self::RANK_4  => self::getRandomName(self::NAME_RANK_4),
            $rank === self::RANK_5  => self::getRandomName(self::NAME_RANK_5),
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
            $rich >= 0 && $rich <= 120 => self::RANK_1,
            $rich >= 121 && $rich <= 240 => self::RANK_2,
            $rich >= 241 && $rich <= 360 => self::RANK_3,
            $rich >= 361 && $rich <= 480 => self::RANK_4,
            $rich >= 481 => self::RANK_5,
            default => self::RANK_1,
        };

        return $rank;
    }

    public static function addNewProfile(string $rank)
    {
        $message = match (true) {
            $rank === self::RANK_1  => self::ADD_NEW_PROFILE_RANK_1[array_rand(self::ADD_NEW_PROFILE_RANK_1)],
            $rank === self::RANK_2  => self::ADD_NEW_PROFILE_RANK_2[array_rand(self::ADD_NEW_PROFILE_RANK_2)],
            $rank === self::RANK_3  => self::ADD_NEW_PROFILE_RANK_3[array_rand(self::ADD_NEW_PROFILE_RANK_3)],
            $rank === self::RANK_4  => self::ADD_NEW_PROFILE_RANK_4[array_rand(self::ADD_NEW_PROFILE_RANK_4)],
            $rank === self::RANK_5  => self::ADD_NEW_PROFILE_RANK_5[array_rand(self::ADD_NEW_PROFILE_RANK_5)],
            default =>  self::ADD_NEW_PROFILE_RANK_1[array_rand(self::ADD_NEW_PROFILE_RANK_1)],
        };

        return $message;
    }

    public static function getHelloText(City $city):string {
        $rank = $city->getRank();
        $message = match (true) {
            $rank === self::RANK_1  => TextCity::HELLO_RANK_1,
            $rank === self::RANK_2  => TextCity::HELLO_RANK_2,
            default =>  TextCity::HELLO_RANK_1,
        };

        $message = str_replace("CITY_NAME",$city->getName(), $message);

        $people = "";
        foreach ($city->getAllCharacter() as $value) {
            $people.=$value->getName() . " " . $value->getLastName() .", ";
        }

        $people=substr($people,0,strlen($people)-2);

        $message .= $people;

        $message.= "\n\n".CommandText::getAllCommand();
        return $message;
    }

}
