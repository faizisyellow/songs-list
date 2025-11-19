<?php
namespace Packages\CommandParser;

use InvalidArgumentException;

class Commander
{
    private array $args;

    /** @var Command[] */
    private array $commands = [];

    private DefaultCmd $defCommand;

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
        $this->defCommand = $dftcmd;
    }

    /**
     * Execute calls user-define function to run
     * the application
     * @return void
     */
    public function Execute(): void
    {
        if (count($this->args) == 1) {
            // Todo: make sure there's a default command
            $this->RunDefaultCmd();
        }
    }

    /**
     * DisplayMessage shows message to the command line.
     * @param string $msg
     * @return void
     */
    public function DisplayMessage(string $msg): void
    {
        echo "$msg \n";
    }

    private function RunDefaultCmd(): void
    {
        $defaultCmd = $this->defCommand;

        if (isset($defaultCmd)) {
            $msg = "{$defaultCmd->description}\n";

            $msg .= "\nUsage:\n\n php {main file}.php [command]\n";

            $msg .= "\nAvailable Commands:\n\n";

            if (count($this->commands) >= 1) {
                foreach ($this->commands as $cmd) {
                    $msg .= " {$cmd->use}";
                }
            } else {
                $msg .= "\n not have any commands yet.\n";
            }

            $this->DisplayMessage($msg);
        }
    }
}
