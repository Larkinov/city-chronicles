<?php

namespace CityChronicles\City;

use CityChronicles\Character\Character;
use CityChronicles\Character\CharacterFabric;
use CityChronicles\Time\Time;
use CityChronicles\God\God;
use CityChronicles\God\GodFabric;
use CityChronicles\Text\TextCity;
use CityChronicles\Time\FabricLastTime;
use CityChronicles\Time\TemplateLastTime;
use Exception;
use vkbot_conversation\classes\conversation\Conversation;

class City
{

    private string|null $name;
    private int|null $rich;
    private string|null $rank;
    private array|null $effPlace;
    private string $initConversation = "";
    private array $godsID = [];
    private $conversation;

    private Time $time;
    private TemplateLastTime $eventTime;
    private FabricLastTime $fabricLastTime;

    public function __construct(
        public string $peer_id,
        private string $storagePath,
        private string $storagePathProfile,
        private string $storagePathCharacter
    ) {
        $this->conversation = new Conversation($peer_id, $storagePath, $storagePathProfile);
        $this->time = new Time($this->conversation);
        $this->fabricLastTime = new FabricLastTime($this->conversation);
        $this->eventTime = $this->fabricLastTime->getInstance("lastTimeEvent");
    }

    public function getRich(): int
    {
        return $this->rich;
    }

    public function setRich(int $rich)
    {
        $this->rich = $rich;
        $this->conversation->saveInfo("rich", strval($rich));
    }

    public function getRank(): string
    {
        return $this->rank;
    }

    public function setRank(string $rank)
    {
        $newrank = match (true) {
            $rank === TextCity::RANK_1 => TextCity::RANK_1,
            $rank === TextCity::RANK_2 => TextCity::RANK_2,
            default => throw new Exception("undefined RANK - $rank"),
        };
        $this->rank = $newrank;
        $this->conversation->saveInfo("rank", $newrank);
    }

    public function getName()
    {
        return $this->name;
    }


    public function setName(string $name)
    {
        $newname = match (true) {
            in_array($name, TextCity::NAME_RANK_1) => $name,
            in_array($name, TextCity::NAME_RANK_2) => $name,
            default => throw new Exception("undefined NAME - $name"),
        };
        $this->name = $newname;
        $this->conversation->saveInfo("name", $newname);
    }

    public function getEffPlace()
    {
        return $this->effPlace;
    }

    public function setEffPlace(object|array $effPlace)
    {
        $arr = [];
        if (is_object($effPlace)) {

            foreach ($effPlace as $value) {
                array_push($arr, $value);
            }
        } else
            $arr = $effPlace;


        $this->effPlace = $arr;
        $this->conversation->saveInfo("effPlace", $effPlace);
    }

    public function getInitConversation()
    {
        return $this->initConversation;
    }

    public function setInitConversation(string $initConversation)
    {
        $this->initConversation = $initConversation;
        $this->conversation->saveInfo("initConversation", $initConversation);
    }

    public function setGodsID(string|object $god)
    {
        if (is_string($god)) {
            array_push($this->godsID, $god);
        } else {
            foreach ($god as $value) {
                if (!in_array($value, $this->godsID))
                    array_push($this->godsID, $value);
            }
        }
        $this->conversation->saveInfo("godsID", $this->godsID);
    }

    public function getGodsID()
    {
        return $this->godsID;
    }

    public function getGod(string $id): God
    {
        return GodFabric::getGod($id, $this->peer_id, $this->storagePathProfile);
    }
    public function getAllGods(): array
    {
        $arr = [];
        foreach ($this->godsID as $id) {
            $god = GodFabric::getGod($id, $this->peer_id, $this->storagePathProfile);
            array_push($arr, $god);
        }
        return $arr;
    }

    public function getCharacter(string $id): Character
    {
        return CharacterFabric::getCharacter($id, $this->peer_id, $this->storagePathCharacter);
    }
    public function getAllCharacter(): array
    {
        $arr = [];
        foreach ($this->godsID as $id) {
            $god = CharacterFabric::getCharacter($id, $this->peer_id, $this->storagePathCharacter);
            array_push($arr, $god);
        }
        return $arr;
    }

    public function getRandomCharacter()
    {
        $id = $this->godsID[array_rand($this->godsID)];
        return CharacterFabric::getCharacter($id, $this->peer_id, $this->storagePathCharacter);
    }

    public function getLastTimeEvent(): int
    {
        return $this->eventTime->getLastTime();
    }


    public function updateLastTimeEvent()
    {
        $this->eventTime->updateLastTime();
    }

    public function getWaitingHoursEventTime(): int
    {
        return $this->eventTime->getWaitingHoursTime();
    }



    public function getTimeLive(): int
    {
        return $this->time->getTimeLive();
    }

    public function getTimeNow()
    {
        return time();
    }

    public function getDayLive()
    {
        return floor($this->getTimeLive() / 3600 / 24);
    }
}
