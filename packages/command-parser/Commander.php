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
     * @param string $description The base description what the app is about
     */
    public function DefaultCmd(string $description, ?callable $run = null): void
    {
        echo "$description\n";
        if (isset($run)) {
            // Todo: still considering should passed all args or not
            $run($this->args);
        }
    }
}
