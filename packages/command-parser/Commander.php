<?php
namespace Packages\CommandParser;

use InvalidArgumentException;

class Commander
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
    public function AddCmd(Command $cmd): void
    {
        if (!$cmd instanceof Command) {
            throw new InvalidArgumentException("unknown cmd type");
        }

        $this->commands[] = $cmd;
    }

    /**
     * default command when user run the application
     * without any commads/arguments.
     * @param DefaultCmd $dftcmd
     */
    public function DefaultCmd(DefaultCmd $dftcmd): void
    {
        $this->commands[] = $dftcmd;
    }
}
