<?php

namespace vkbot_conversation\classes\message;

class Action
{

    public const ACTION_PHOTO_UPDATE = "CHAT_PHOTO_UPDATE";
    public const ACTION_PHOTO_REMOVE = "CHAT_PHOTO_REMOVE";
    public const ACTION_CREATE = "CHAT_CREATE";
    public const ACTION_TITLE_UPDATE = "CHAT_TITLE_UPDATE";
    public const ACTION_INVITE_USER = "CHAT_INVITE_USER";
    public const ACTION_KICK_USER = "CHAT_KICK_USER";
    public const ACTION_PIN_MESSAGE = "CHAT_PIN_MESSAGE";
    public const ACTION_UNPIN_MESSAGE = "CHAT_UNPIN_MESSAGE";
    public const ACTION_INVITE_USER_BY_LINK = "CHAT_INVITE_USER_BY_LINK";


    public function __construct(
        private string $type,
        private string|null $member_id,
        private string|null $text,
    ) {
    }


    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type)
    {
        if (
            $type === self::ACTION_CREATE
            || $type === self::ACTION_INVITE_USER
            || $type === self::ACTION_INVITE_USER_BY_LINK
            || $type === self::ACTION_KICK_USER
            || $type === self::ACTION_PHOTO_REMOVE
            || $type === self::ACTION_PHOTO_UPDATE
            || $type === self::ACTION_PIN_MESSAGE
            || $type === self::ACTION_TITLE_UPDATE
            || $type === self::ACTION_UNPIN_MESSAGE
        )
            $this->type = $type;
        else
            throw new \Exception("type is not valid");
    }

    public function getMemberId(): string|null
    {
        return $this->member_id;
    }

    public function setMemberId(string $member_id)
    {
        $this->member_id = $member_id;
    }


    public function getText(): string|null
    {
        return $this->text;
    }

    public function setText(string $text)
    {
        $this->text = $text;
    }
}
