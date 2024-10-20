<?php

namespace vkbot_conversation\classes;

use vkbot_conversation\classes\bot\BotCommands;
use vkbot_conversation\classes\message\MessageEvent;

require_once(__DIR__ . "/../Config.php");

class Event
{
    const TYPE_MESSAGE_FOR_BOT = "MESSAGE_FOR_BOT";
    const TYPE_MESSAGE_OTHER_COMMAND = "MESSAGE_OTHER_COMMAND";
    const TYPE_CHAT_EVENT = "CHAT_EVENT";

    private string|null $typeEvent = null;

    public function __construct(private MessageEvent $eventData, private BotCommands $botCommands)
    {
        $isBotCommand = $this->isMessageForBot($eventData);

        if ($isBotCommand)
            $this->typeEvent = Event::TYPE_MESSAGE_FOR_BOT;
        elseif ($isBotCommand === null)
            $this->typeEvent = Event::TYPE_MESSAGE_OTHER_COMMAND;
        if ($this->isChatEvent($eventData))
            $this->typeEvent = Event::TYPE_CHAT_EVENT;
    }

    private function isMessageForBot(MessageEvent $message): bool | null
    {
        $text = $message->getText();
        if ($text) {
            $result = false;
            $text = mb_strtoupper($text);
            $arrCheckers = [];
            foreach (\BOT_NAME_VK_CONVERSATION as $key => $value) {
                $value = mb_strtoupper($value);
                if (stripos($text, "/") !== false && (stripos($text, $value) !== false || stripos($text, $value) !== false)) {
                    $command = substr($text, stripos($text, "/"));
                    if (in_array($command, $this->botCommands->getCommandsName())) {
                        $arrCheckers[$key] = true;
                        break;
                    } else {
                        $arrCheckers[$key] = null;
                    }
                } else
                    $arrCheckers[$key] = false;
            }

            if (in_array(true, $arrCheckers))
                return true;
            else {
                $isNull = false;
                foreach ($arrCheckers as $value) {
                    if ($value === null) {
                        $isNull = true;
                        break;
                    }
                }
                if ($isNull)
                    return null;
                else
                    return false;
            }
            return $result;
        } else
            return false;
    }

    private function getCommand(string $text): ?string
    {
        $isCommand =  mb_strtoupper(substr($text, stripos($text, "/")));
        if (in_array($isCommand, $this->botCommands->getCommandsName())) {
            $fullCommand = false;
            foreach ($this->botCommands->getCommandsName() as $command) {
                if ($command === $isCommand) {
                    $isCommand = $command;
                    $fullCommand = true;
                }
            }
            if ($fullCommand)
                return $isCommand;
            else
                return null;
        } else
            return null;
    }

    private function isChatEvent(MessageEvent $message): bool
    {
        if ($message->getAction())
            return true;
        else
            return false;
    }

    public function runEvent()
    {
        switch ($this->typeEvent) {
            case Event::TYPE_MESSAGE_FOR_BOT:
                $command = $this->getCommand($this->eventData->getText());
                if ($command)
                    $this->botCommands->runBotCommand($this->eventData, $command);
                break;
            case Event::TYPE_CHAT_EVENT:
                $this->botCommands->runBotChatEvent($this->eventData);
                break;
            case Event::TYPE_MESSAGE_OTHER_COMMAND:
                $this->botCommands->runBotOtherCommand($this->eventData);
        }
    }
}
