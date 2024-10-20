<?php

namespace vkbot_conversation\classes\bot;

use vkbot_conversation\classes\message\MessageEvent;

class BotCommands
{
    private array $commands = [""];
    private array $commandsNameChatEvent = [""];
    private array $commandsName = [""];

    private $functionOtherCommand = null;

    public function registerNewCommand(string $nameNewCommand, callable $newCommand)
    {
        $name = mb_strtoupper($nameNewCommand);
        array_push($this->commandsName, $name);
        $this->commands[$nameNewCommand] = $newCommand;
    }

    public function registerNewCommandChatEvent(string $nameNewCommand, callable $newCommand)
    {
        $this->commandsNameChatEvent[$nameNewCommand] = $newCommand;
    }

    public function runBotCommand(MessageEvent $message, string $nameCommand)
    {
        foreach ($this->commands as $key => $value) {
            if (mb_strtoupper($key) === mb_strtoupper($nameCommand))
                call_user_func_array($value, [$message]);
        }
    }
    public function runBotChatEvent(MessageEvent $message)
    {
        foreach ($this->commandsNameChatEvent as $key => $value) {
            if (mb_strtoupper($key) === mb_strtoupper($message->getAction()->getType()))
                call_user_func_array($value, [$message]);
        }
    }

    public function getCommandsName(): array
    {
        return $this->commandsName;
    }
    public function getCommandsNameChatEvent(): array
    {
        return $this->commandsNameChatEvent;
    }

    public function registerFuncOtherCommand($func)
    {
        $this->functionOtherCommand = $func;
    }

    public function runBotOtherCommand(MessageEvent $message)
    {
        if (is_callable($this->functionOtherCommand))
            call_user_func_array($this->functionOtherCommand, [$message]);
    }
}
