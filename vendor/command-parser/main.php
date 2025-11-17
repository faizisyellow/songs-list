<?php

use Cmd\Command;

class CommandParser
{
    private array $args;
    private array $commands = [];

    public function __construct()
    {
        $this->args = $GLOBALS["argv"];
    }

    /**
     * AddCmd adds new cmd handler to collection of commands
     * @param Command $cmd
     * @return void
     * @throws InvalidArgumentException
     * */
    public function AddCmd(Cmd\Command $cmd): void
    {
        if (!$cmd instanceof Command) {
            throw new InvalidArgumentException("unknown cmd type");
        }

        $this->commands[] = $cmd;
    }
}
