<?php

namespace CityChronicles\Command;

use CityChronicles\Casino\Casino;
use CityChronicles\Text\TextCharacter;
use CityChronicles\City\City;
use CityChronicles\City\CityFabric;
use CityChronicles\Text\TextCity;
use CityChronicles\Config;
use CityChronicles\Event\EventFabric;
use CityChronicles\Settings\Settings;
use CityChronicles\Text\CommandText;
use CityChronicles\Utils\Logs;
use CityChronicles\Utils\TextConverter;
use vkbot_conversation\classes\bot\Bot;
use vkbot_conversation\classes\bot\BotCommands;
use vkbot_conversation\classes\Event;
use vkbot_conversation\classes\message\MessageEvent;
use vkbot_conversation\classes\server\ServerVK;
use vkbot_conversation\classes\message\Action;

class CommandInit
{

    private static function getInfoMessage(MessageEvent $message): string
    {
        $log = "id - " . $message->getId()
            . "; date - " . $message->getDate()
            . "; peer_id - " . $message->getPeerId()
            . "; from_id - " . $message->getFromId()
            . "; text - " . $message->getText()
            . "; member count - " .  json_encode($message->getMembersCount())
            . "; action - " .  json_encode($message->getAction());

        return $log;
    }

    private static function checkingStartBot(MessageEvent $message): City|false
    {
        $city = CityFabric::getCity($message->getPeerId(), Config::PATH_CITY, Config::PATH_GODS, Config::PATH_CHARACTERS);

        if ($city->getInitConversation() === "init") {
            return $city;
        } else {
            $bot = new Bot();
            $bot->sendMessage(Settings::NEED_START, $message->getPeerId());
            return false;
        }
    }

    private static function checkNewRank(string $oldRank, string $newRank, City $city): string | false
    {
        if ($oldRank !== $newRank) {
            $city->setRank($newRank);
            $city->setName(TextCity::getName($newRank));
            $text = TextCity::getHelloText($city);
            return $text;
        } else
            return false;
    }

    static public function init(ServerVK $server)
    {

        $startBot = function (MessageEvent $message) {
            Logs::writeLog(Logs::MAIN_LOG, "start command " . CommandText::START_BOT . "; " . $message->getPeerId() . " - peer_id; " . $message->getFromId() . " - from_id;");
            Logs::writeLog(Logs::FULL_MESSAGE_LOG, "start command " . CommandText::START_BOT . "; " .  self::getInfoMessage($message));
            $city = CityFabric::getCity($message->getPeerId(), Config::PATH_CITY, Config::PATH_GODS, Config::PATH_CHARACTERS);

            if ($city->getInitConversation() !== "init") {
                $messageText = TextCity::getHelloText($city);
                $city->setInitConversation("init");
            } else
                $messageText = Settings::ALREADY_START;

            $bot = new Bot();
            $bot->sendMessage($messageText, $message->getPeerId());

            Logs::writeLog(Logs::MAIN_LOG, "end " . CommandText::START_BOT);
        };


        $infoCity = function (MessageEvent $message) {
            Logs::writeLog(Logs::MAIN_LOG, "start command " . CommandText::INFO_CITY . "; " . $message->getPeerId() . " - peer_id; " . $message->getFromId() . " - from_id;");
            Logs::writeLog(Logs::FULL_MESSAGE_LOG, "start command " . CommandText::INFO_CITY . "; " .  self::getInfoMessage($message));

            $city = self::checkingStartBot($message);
            if ($city !== false) {
                $people = $city->getAllCharacter();
                $peopleName = "";
                foreach ($people as $value) {
                    $peopleName .= $value->getName() . " " . $value->getLastName() . ", ";
                }
                $peopleName = substr($peopleName, 0, strlen($peopleName) - 2);
                $messageText = "Название: " . $city->getName() . "\nРанг: " . $city->getRank() . "\nБогатство: " . $city->getRich() . "\nЖители: $peopleName\nПрожито дней: " . $city->getDayLive();
                $bot = new Bot();
                $bot->sendMessage($messageText, $message->getPeerId());
            }
            Logs::writeLog(Logs::MAIN_LOG, "end " . CommandText::INFO_CITY);
        };


        $infoCharacter = function (MessageEvent $message) {
            Logs::writeLog(Logs::MAIN_LOG, "start command " . CommandText::INFO_PLAYER . "; " . $message->getPeerId() . " - peer_id; " . $message->getFromId() . " - from_id;");
            Logs::writeLog(Logs::FULL_MESSAGE_LOG, "start command " . CommandText::INFO_PLAYER . "; " .  self::getInfoMessage($message));

            $city = self::checkingStartBot($message);
            if ($city !== false) {
                $character = $city->getCharacter($message->getFromId());
                $messageText = "Имя: " . $character->getName() . " " . $character->getLastName() . "\nВозраст: " . $character->getAge() . "\nЗдоровье: " . $character->getHealth() . "\nДеньги: " . $character->getMoney() . "\nПрофессия: " . $character->getProfession();
                $bot = new Bot();
                $bot->sendMessage($messageText, $message->getPeerId());
            }
            Logs::writeLog(Logs::MAIN_LOG, "end " . CommandText::INFO_PLAYER);
        };

        $statisticAll = function (MessageEvent $message) {
            Logs::writeLog(Logs::MAIN_LOG, "start command " . CommandText::STATISTIC_ALL . "; " . $message->getPeerId() . " - peer_id; " . $message->getFromId() . " - from_id;");
            Logs::writeLog(Logs::FULL_MESSAGE_LOG, "start command " . CommandText::STATISTIC_ALL . "; " . self::getInfoMessage($message));





            $city = self::checkingStartBot($message);
            if ($city !== false) {
                $characters = $city->getAllCharacter();

                usort($characters, function ($a, $b) {
                    return intval($b->getLuckyCount()) > intval($a->getLuckyCount());
                });
                $messageText = TextCharacter::getPositionText($characters, true);

                usort($characters, function ($a, $b) {
                    return intval($b->getUnluckyCount()) > intval($a->getUnluckyCount());
                });
                $messageText .= "\n" . TextCharacter::getPositionText($characters, false);

                $bot = new Bot();
                $bot->sendMessage($messageText, $message->getPeerId());
            }
            Logs::writeLog(Logs::MAIN_LOG, "end " . CommandText::STATISTIC_ALL);
        };

        $newHumanConversation = function (MessageEvent $message) {
            Logs::writeLog(Logs::MAIN_LOG, Action::ACTION_INVITE_USER . "; " . $message->getPeerId() . " - peer_id; " . $message->getFromId() . " - from_id;");
            Logs::writeLog(Logs::FULL_MESSAGE_LOG, "start command " . Action::ACTION_INVITE_USER . "; " .  self::getInfoMessage($message));

            $city = self::checkingStartBot($message);
            if ($city !== false) {
                $bot = new Bot();
                $character = $city->getCharacter($message->getAction()->getMemberId());
                $text = TextCity::addNewProfile($city->getRank());
                $text = TextConverter::convertTextGenderCommaFullName($text, $character->getIsMan(), $character->getName() . " " . $character->getLastName());
                $bot->sendMessage($text, $message->getPeerId());
            }
            Logs::writeLog(Logs::MAIN_LOG, "end " .  Action::ACTION_INVITE_USER);
        };

        $dailyEvent = function (MessageEvent $message) {
            Logs::writeLog(Logs::MAIN_LOG, "start command " . CommandText::DAILY_EVENT . "; " . $message->getPeerId() . " - peer_id; " . $message->getFromId() . " - from_id;");
            Logs::writeLog(Logs::FULL_MESSAGE_LOG, "start command " . CommandText::DAILY_EVENT . "; " .  self::getInfoMessage($message));

            $city = self::checkingStartBot($message);

            if ($city !== false) {
                $bot = new Bot();
                Settings::getWaitingTimeEvent($city);
                if ($city->getWaitingHoursEventTime() > Settings::WAITING_EVENT_HOURS_TIME) {
                    $oldRank = $city->getRank();
                    $eventText = EventFabric::startEvent($city);
                    if ($eventText) {
                        $bot->sendMessage($eventText, $message->getPeerId());
                        $city->updateLastTimeEvent();

                        $newRank = TextCity::getRank($city->getRich());

                        $text = CommandInit::checkNewRank($oldRank, $newRank, $city);
                        if ($text)
                            $bot->sendMessage($text, $message->getPeerId());
                    }
                } else {
                    $bot->sendMessage(Settings::getWaitingTimeEvent($city), $message->getPeerId());
                }
            }
            Logs::writeLog(Logs::MAIN_LOG, "end " . CommandText::DAILY_EVENT);
        };



        $sendEventGood = function (MessageEvent $message) {
            Logs::writeLog(Logs::MAIN_LOG, "start command " . CommandText::SEND_EVENT_GOOD . "; " . $message->getPeerId() . " - peer_id; " . $message->getFromId() . " - from_id;");
            Logs::writeLog(Logs::FULL_MESSAGE_LOG, "start command " . CommandText::SEND_EVENT_GOOD . "; " .  self::getInfoMessage($message));

            $city = self::checkingStartBot($message);
            if ($city !== false) {
                $bot = new Bot();
                $character = $city->getCharacter($message->getFromId());
                $time = $character->getWaitingHoursEventTime();
                if ($time > Settings::WAITING_INFLUENTIAL_EVENT_HOURS_TIME) {
                    $oldRank = $city->getRank();
                    $eventText = EventFabric::startEvent($city, Config::EVENT_GOODNESS_ID, $message->getFromId());
                    if ($eventText) {
                        $bot->sendMessage($eventText, $message->getPeerId());

                        $newRank = TextCity::getRank($city->getRich());
                        $text = CommandInit::checkNewRank($oldRank, $newRank, $city);
                        if ($text)
                            $bot->sendMessage($text, $message->getPeerId());
                    }
                } else {
                    $bot->sendMessage(Settings::getWaitingTimeInfluentialEvent($time), $message->getPeerId());
                }
            }
            Logs::writeLog(Logs::MAIN_LOG, "end " . CommandText::SEND_EVENT_GOOD);
        };

        $sendEventEvil = function (MessageEvent $message) {
            Logs::writeLog(Logs::MAIN_LOG, "start command " . CommandText::SEND_EVENT_EVIL . "; " . $message->getPeerId() . " - peer_id; " . $message->getFromId() . " - from_id;");
            Logs::writeLog(Logs::FULL_MESSAGE_LOG, "start command " . CommandText::SEND_EVENT_EVIL . "; " .  self::getInfoMessage($message));

            $city = self::checkingStartBot($message);
            if ($city !== false) {
                $bot = new Bot();
                $character = $city->getCharacter($message->getFromId());
                $time = $character->getWaitingHoursEventTime();
                if ($time > Settings::WAITING_INFLUENTIAL_EVENT_HOURS_TIME) {
                    $oldRank = $city->getRank();
                    $eventText = EventFabric::startEvent($city, Config::EVENT_EVIL_ID, $message->getFromId());
                    if ($eventText) {
                        $bot->sendMessage($eventText, $message->getPeerId());

                        $newRank =  TextCity::getRank($city->getRich());
                        $text = CommandInit::checkNewRank($oldRank, $newRank, $city);
                        if ($text)
                            $bot->sendMessage($text, $message->getPeerId());
                    }
                } else {
                    $bot->sendMessage(Settings::getWaitingTimeInfluentialEvent($time), $message->getPeerId());
                }
            }
            Logs::writeLog(Logs::MAIN_LOG, "end " . CommandText::SEND_EVENT_EVIL);
        };

        $sendMessageOtherCommand = function (MessageEvent $message) {
            Logs::writeLog(Logs::MAIN_LOG, "start command " . CommandText::OTHER_COMMAND . "; " . $message->getPeerId() . " - peer_id; " . $message->getFromId() . " - from_id;");
            Logs::writeLog(Logs::FULL_MESSAGE_LOG, "start command " . CommandText::OTHER_COMMAND . "; " .  self::getInfoMessage($message));

            $city = self::checkingStartBot($message);
            if ($city !== false) {
                $bot = new Bot();
                $bot->sendMessage(Settings::ANOTHER_COMMAND_TEXT, $message->getPeerId());
            }
            Logs::writeLog(Logs::MAIN_LOG, "end " . CommandText::OTHER_COMMAND);
        };

        $help = function (MessageEvent $message) {
            Logs::writeLog(Logs::MAIN_LOG, "start command " . CommandText::HELP . "; " . $message->getPeerId() . " - peer_id; " . $message->getFromId() . " - from_id;");
            Logs::writeLog(Logs::FULL_MESSAGE_LOG, "start command " . CommandText::HELP . "; " .  self::getInfoMessage($message));

            $city = self::checkingStartBot($message);
            if ($city !== false) {
                $bot = new Bot();
                $bot->sendMessage(CommandText::getAllCommand(), $message->getPeerId());
            }
            Logs::writeLog(Logs::MAIN_LOG, "end " . CommandText::HELP);
        };


        $drinkBeer = function (MessageEvent $message) {
            Logs::writeLog(Logs::MAIN_LOG, "start command " . CommandText::DRINK_BEER . "; " . $message->getPeerId() . " - peer_id; " . $message->getFromId() . " - from_id;");
            Logs::writeLog(Logs::FULL_MESSAGE_LOG, "start command " . CommandText::DRINK_BEER . "; " .  self::getInfoMessage($message));

            $city = self::checkingStartBot($message);
            if ($city !== false) {
                $bot = new Bot();
                $character = $city->getCharacter($message->getFromId());
                $time = $character->getWaitingHoursDrinkBeerTime();
                if ($time > Settings::WAITING_DRINK_BEER_HOURS) {
                    $bot->sendMessage("Пивандрий", $message->getPeerId());
                    // $oldRank = $city->getRank();
                    // $eventText = EventFabric::startEvent($city, Config::EVENT_DRINK_TIME_ID, $message->getFromId());
                    // if ($eventText) {
                    //     $bot->sendMessage($eventText, $message->getPeerId());

                    //     $newRank =  TextCity::getRank($city->getRich());
                    //     $text = CommandInit::checkNewRank($oldRank, $newRank, $city);
                    //     if ($text)
                    //         $bot->sendMessage($text, $message->getPeerId());
                    // }
                } else {
                    $bot->sendMessage(Settings::getWaitingTimeDrinkBeer($time), $message->getPeerId());
                }
            }
            Logs::writeLog(Logs::MAIN_LOG, "end " . CommandText::FIND_MONEY);
        };


        // $findMoney = function (MessageEvent $message) {
        //     Logs::writeLog(Logs::MAIN_LOG, "start command " . CommandText::FIND_MONEY . "; " . $message->getPeerId() . " - peer_id; " . $message->getFromId() . " - from_id;");
        // Logs::writeLog(Logs::FULL_MESSAGE_LOG, "start command " . CommandText::FIND_MONEY . "; " .  self::getInfoMessage($message));

        //     $city = self::checkingStartBot($message);
        //     if ($city !== false) {
        //         $bot = new Bot();
        //         $character = $city->getCharacter($message->getFromId());
        //         $time = $character->getWaitingHoursEventTime();
        //         if ($time > Settings::WAITING_EVIL_EVENT_HOURS_TIME) {
        //             $oldRank = $city->getRank();
        //             $eventText = EventFabric::startEvent($city, -1, $message->getFromId());
        //             if ($eventText) {
        //                 $bot->sendMessage($eventText, $message->getPeerId());

        //                 $newRank =  TextCity::getRank($city->getRich());
        //                 $text = CommandInit::checkNewRank($oldRank, $newRank, $city);
        //                 if ($text)
        //                     $bot->sendMessage($text, $message->getPeerId());
        //             }
        //         } else {
        //             $bot->sendMessage(Settings::getWaitingTimeInfluentialEvent($time), $message->getPeerId());
        //         }
        //     }
        //     Logs::writeLog(Logs::MAIN_LOG, "end " . CommandText::FIND_MONEY);
        // };


        // $casino = function (MessageEvent $message) {
        //     Logs::writeLog(Logs::MAIN_LOG, "start command " . CommandText::CASINO . "; " . $message->getPeerId() . " - peer_id; " . $message->getFromId() . " - from_id;");
        // Logs::writeLog(Logs::FULL_MESSAGE_LOG, "start command " . CommandText::CASINO . "; " .  self::getInfoMessage($message));

        //     $city = self::checkingStartBot($message);
        //     if ($city !== false) {
        //         $character = $city->getCharacter($message->getFromId());
        //         $time = $character->getWaitingHoursEventTime();
        //         if ($time > Settings::WAITING_EVIL_EVENT_HOURS_TIME) {
        //             $casino = new Casino();
        //             $oldRank = $city->getRank();
        //             $eventText = EventFabric::startEvent($city, -1, $message->getFromId());
        //             if ($eventText) {
        //                 $bot->sendMessage($eventText, $message->getPeerId());

        //                 $newRank =  TextCity::getRank($city->getRich());
        //                 $text = CommandInit::checkNewRank($oldRank, $newRank, $city);
        //                 if ($text)
        //                     $bot->sendMessage($text, $message->getPeerId());
        //             }
        //         } else {
        //             $bot->sendMessage(Settings::getWaitingTimeInfluentialEvent($time), $message->getPeerId());
        //         }
        //         $bot = new Bot();
        //         $bot->sendMessage(CommandText::getAllCommand(), $message->getPeerId());
        //     }
        //     Logs::writeLog(Logs::MAIN_LOG, "end " . CommandText::CASINO);
        // };

        $bc = new BotCommands();
        $bc->registerNewCommand(CommandText::START_BOT, $startBot);
        $bc->registerNewCommand(CommandText::INFO_CITY, $infoCity);
        $bc->registerNewCommand(CommandText::INFO_PLAYER, $infoCharacter);
        $bc->registerNewCommand(CommandText::DAILY_EVENT, $dailyEvent);
        $bc->registerNewCommand(CommandText::STATISTIC_ALL, $statisticAll);
        $bc->registerNewCommand(CommandText::SEND_EVENT_GOOD, $sendEventGood);
        $bc->registerNewCommand(CommandText::SEND_EVENT_EVIL, $sendEventEvil);
        $bc->registerNewCommand(CommandText::HELP, $help);
        $bc->registerNewCommand(CommandText::DRINK_BEER, $drinkBeer);
        // $bc->registerNewCommand(CommandText::FIND_MONEY, $findMoney);
        // $bc->registerNewCommand(CommandText::CASINO, $casino);

        $bc->registerNewCommandChatEvent(Action::ACTION_INVITE_USER, $newHumanConversation);

        $bc->registerFuncOtherCommand($sendMessageOtherCommand);

        echo '<pre>';

        try {
            Logs::writeLog(Logs::MAIN_LOG, "connection server *****************");
            $server->connection();
            $eventsData = $server->getEventsData();
            foreach ($eventsData as $eventData) {
                Logs::writeLog(Logs::MAIN_LOG, "start VK message event");
                $event = new Event($eventData, $bc);
                $resultEvent = $event->runEvent();
                if ($resultEvent === true) {
                    Logs::writeLog(Logs::MAIN_LOG, "end VK message event");
                } else {
                    Logs::writeLog(Logs::MAIN_LOG, "message event error: $resultEvent");
                    try {
                        if (stripos($resultEvent, "You don't have access to this chat") !== false) {
                            $bot = new Bot();
                            $bot->sendMessage(Settings::DONT_HAVE_PERMISSION, $eventData->getPeerId());
                        }
                    } catch (\Throwable $th) {
                        Logs::writeLog(Logs::MAIN_LOG, "error CATCH-ERROR: " . $th->getMessage());
                    }
                }
            }
            Logs::writeLog(Logs::MAIN_LOG, "end *****************");
        } catch (\Throwable $th) {
            print_r($th);
            Logs::writeLog(Logs::MAIN_LOG, "error CommandInit.php: " . json_encode($th) . "*****************");
        }
    }
}
