<?php

namespace vkbot_conversation\classes\message;

use vkbot_conversation\classes\message\Action;

class MessageEvent
{

    public function __construct(
        private string $id,
        private string $date,
        private string $peer_id,
        private string $from_id,
        private string $text,
        private string|null $members_count,
        private Action|null $action,
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id)
    {
        $this->id = $id;
    }

    public function getDate(): string
    {
        return $this->date;
    }

    public function setDate(string $date)
    {
        $this->date = $date;
    }

    public function getPeerId(): string
    {
        return $this->peer_id;
    }

    public function setPeerId(string $peer_id)
    {
        $this->peer_id = $peer_id;
    }

    public function getFromId(): string
    {
        return $this->from_id;
    }

    public function setFromId(string $from_id)
    {
        $this->from_id = $from_id;
    }

    public function getMembersCount(): string|null
    {
        return $this->members_count;
    }

    public function setMembersCount(string $members_count)
    {
        $this->members_count = $members_count;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text)
    {
        $this->text = $text;
    }

    public function getAction(): Action|null
    {
        return $this->action;
    }

    public function setAction(Action $action)
    {
        $this->action = $action;
    }
}
