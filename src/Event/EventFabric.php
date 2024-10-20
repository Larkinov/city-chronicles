<?php

namespace CityChronicles\Event;

use CityChronicles\City\City;
use CityChronicles\Text\TextCity;
use CityChronicles\Config;
use CityChronicles\Settings\Settings;
use CityChronicles\Text\EventText;
use CityChronicles\Utils\Logs;

trait EventReader
{
    public static function getEventsData(string $rank, int $typeEvent): array|false
    {
        $filename = match (true) {
            $typeEvent === -1 => Config::FILENAME_EVENTS_EVIL,
            $typeEvent === 1 => Config::FILENAME_EVENTS_GOODNESS,
            $typeEvent === 0 => Config::FILENAME_EVENTS_NEUTRAL,
        };
        $rank = match (true) {
            $rank === TextCity::RANK_1 => "/rank_1",
            $rank === TextCity::RANK_2 => "/rank_2",
            $rank === TextCity::RANK_3 => "/rank_3",
            $rank === TextCity::RANK_4 => "/rank_4",
            $rank === TextCity::RANK_5 => "/rank_5",
        };

        $path = __DIR__ . "/.." . Config::PATH_EVENTS . $rank . $filename;

        try {
            $fr = fopen($path, "r");

            if ($fr) {
                $rows = [];
                $fields = [];
                while (($row = fgets($fr, 4096)) !== false) {
                    $rowData = explode(",", trim($row));

                    // Если заголовки еще не установлены
                    if (empty($fields)) {
                        $fields = $rowData; // Сохраняем заголовки
                    } else {
                        // Создаем ассоциативный массив
                        $rows[] = array_combine($fields, $rowData);
                    }
                }

                if (!feof($fr))
                    throw new \Exception("Ошибка: неожиданный сбой функций fgets()");

                fclose($fr);
            }
        } catch (\Throwable $th) {
            print_r($th);
            fclose($fr);
            return false;
        }
        return $rows;
    }
}

class EventFabric
{

    private static function getNeutralEff(string $eff, int $isMan)
    {
        if ($eff) {
            $arr = preg_replace_callback('/(\S+)\/(\S+)/', function ($matches) use ($isMan) {
                return $isMan === 1 ? $matches[1] : $matches[2];
            }, $eff);
            $arr = explode("~", $arr);
            $rand = array_rand($arr);
            return $arr[$rand];
        }
        return false;
    }

    private static function getEvent(City $city, int $typeEvent): ModelEvent| false
    {
        if ($typeEvent === 100)
            $typeEvent = Settings::getRandomTypeEvent();

        // $typeEvent = 0;
        $rankEvent = $city->getRank();
        $eventsData = EventReader::getEventsData($rankEvent, $typeEvent);
        if (!$eventsData)
            return false;
        else {
            $rand = array_rand($eventsData);

            if ($typeEvent !== 0) {
                $event = new InfluentialEvent(
                    $eventsData[$rand]['id'],
                    $typeEvent,
                    $rankEvent,
                    intval($eventsData[$rand]['count']),
                    $eventsData[$rand]['next_event_id'],
                    $eventsData[$rand]['text'],
                    $eventsData[$rand]['time'],
                    intval($eventsData[$rand]['eff_mana']),
                    intval($eventsData[$rand]['eff_plus_rich']),
                    $eventsData[$rand]['place'],
                );
                return $event;
            } else {

                $event = new NeutralEvent(
                    $eventsData[$rand]['id'],
                    $typeEvent,
                    $rankEvent,
                    intval($eventsData[$rand]['count']),
                    $eventsData[$rand]['next_event_id'],
                    $eventsData[$rand]['text'],
                    $eventsData[$rand]['time'],
                    $eventsData[$rand]['eff_name_character'],
                    $eventsData[$rand]['eff_lastname_character'],
                    $eventsData[$rand]['eff_proffesion'],
                    $eventsData[$rand]['eff_name_city'],
                    intval($eventsData[$rand]['eff_plus_rich']),
                    $eventsData[$rand]['eff_place_add'],
                );
                return $event;
            }
        }
    }

    private static function getCharactersEvents(City $city, int $count)
    {
        if ($count !== -1) {
            $randCharacters = [];
            for ($i = 0; $i < $count;) {
                $character = $city->getRandomCharacter();
                if (!array_key_exists($character->getId(), $randCharacters)) {
                    $i++;
                    $randCharacters[$character->getId()] = $character;
                }
            }
        } else {
            $randCharacters = $city->getAllCharacter();
        }

        return $randCharacters;
    }

    private static function goEvent(ModelEvent $event, array $randCharacters, City $city): string
    {

        $newText = $event->getText();
        $isMan = 0;
        $i = 1;
        $arrIdCharacters = [];

        foreach ($randCharacters as $value) {
            $isMan = $value->getIsMan();
            $name = $value->getName() . " " . $value->getLastName();
            $newText = str_replace("CHARACTER_$i", $name, $newText);
            $i++;

            $arrIdCharacters[$value->getId()] = true;

            //replace male/female text
            if ($event->getCountCharacter() === 1) {
                $outputString = preg_replace_callback('/(\S+)\/(\S+)/', function ($matches) use ($isMan) {
                    return $isMan === 1 ? $matches[1] : $matches[2];
                }, $newText);
                $newText = $outputString;
            }

            //plus count
            if ($event->getType() === -1)
                $value->setUnluckyCount($value->getUnluckyCount() + 1);
            if ($event->getType() === 1)
                $value->setLuckyCount($value->getLuckyCount() + 1);

            //neutral event
            if ($event->getType() === 0) {
                $nameCharacter = self::getNeutralEff($event->getEffNameCharacter(), $isMan);
                $lastNameCharacter = self::getNeutralEff($event->getEffLastNameCharacter(), $isMan);
                $profession = self::getNeutralEff($event->getEffProfession(), $isMan);
                $place = $event->getEffPlace();

                if ($nameCharacter) {
                    $newText = str_replace("NAME_CHARACTER", $nameCharacter, $newText);
                    $value->setName($nameCharacter);
                }
                if ($lastNameCharacter) {
                    $newText = str_replace("LASTNAME_CHARACTER", $lastNameCharacter, $newText);
                    $value->setLastName($lastNameCharacter);
                }
                if ($profession) {
                    $newText = str_replace("PROFESSION", $profession, $newText);
                    $value->setProfession($profession);
                }

                if ($place) {
                    $places = $city->getEffPlace();
                    array_push($places, $place);
                    $city->setEffPlace($places);
                }

                if ($event->getEffNameCity()) {
                    $newName = TextCity::getName($city->getRank());
                    $city->setName($newName);
                    $newText = str_replace("NAME_CITY", $newName, $newText);
                }
            }
        }


        $isAll = $event->getCountCharacter() === -1 ? true : false;

        if ($event->getType() === -1)
            $newText = EventText::getEvilText($arrIdCharacters, $city->getAllGods(), $isAll) . $newText;

        if ($event->getType() === 1)
            $newText = EventText::getGoodText($arrIdCharacters, $city->getAllGods(), $isAll) . $newText;

        if ($event->getType() === 0)
            $newText = EventText::getNeutralText($arrIdCharacters, $city->getAllGods(), $isAll) . $newText;


        $newText = str_replace("~", ",", $newText);

        $rich1 = intval($city->getDayLive()) + intval($event->getEffRich());
        $rich2 = $city->getRich() + intval($event->getEffRich());
        $rich1 > $rich2 ? $city->setRich($rich1) : $city->setRich($rich2);

        return $newText;
    }

    private static function getRandomNotNeutralEvent()
    {
        if (rand(0, 1) === 1)
            return 1;
        else
            return -1;
    }


    private static function workBaseScript(
        City $city,
        ModelEvent $event,
        int $type = 100,
        string $godId = "",
    ): string {
        $randCharacters = self::getCharactersEvents($city, $event->getCountCharacter());
        if ($godId) {
            $character = $city->getCharacter($godId);
            $character->setLastSendEventTime($city->getTimeNow());
        }
        $textEvent = self::goEvent($event, $randCharacters, $city);
        Logs::writeLog(Logs::FULL_LOG, "text event - " . str_replace("\n", " ", $textEvent));

        if ($event->getType() === 0) {
            $secondTextEvent = self::startEvent($city, $type, $godId, true);
            if ($secondTextEvent) {
                $textEvent .= "\n\n" . $secondTextEvent;
            }
        }
        return $textEvent;
    }

    public static function startEvent(
        City $city,
        int $type = 100,
        string $godId = "",
        bool $secondEvent = false,
    ): string|false {
        Logs::writeLog(Logs::FULL_LOG, "event; start getEvent");
        $event = $secondEvent ? self::getEvent($city, self::getRandomNotNeutralEvent()) : self::getEvent($city, $type);

        Logs::writeLog(Logs::FULL_LOG, "event id - " . $event->getId());
        if ($event !== false) {
            Logs::writeLog(Logs::FULL_LOG, "event; result getEvent - true");
            $effPlace = $city->getEffPlace() ? $city->getEffPlace() : [];
            if ($event->getType() === 0) {
                if (in_array($event->getEffPlace(), $effPlace)) {
                    Logs::writeLog(Logs::FULL_LOG, "event; effPlace in City! repeat startEvent()");
                    $secondTextEvent = self::startEvent($city, $type, $godId, true);
                    if ($secondTextEvent) {
                        $textEvent = $secondTextEvent;
                    } else
                        $textEvent = false;
                } else
                    $textEvent = self::workBaseScript($city, $event, $type, $godId);
            } else {
                if (empty($event->getPlace()))
                    $textEvent = self::workBaseScript($city, $event, $type, $godId);
                else {
                    if (in_array($event->getPlace(), $effPlace))
                        $textEvent = self::workBaseScript($city, $event, $type, $godId);
                    else
                        $textEvent = self::startEvent($city, $type, $godId);
                }
            }
        } else {
            $textEvent = false;
            Logs::writeLog(Logs::FULL_LOG, "event; result getEvent - false");
        }

        return $textEvent;
    }
}
