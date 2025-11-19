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

    /**
     * Execute calls user-define function to run
     * the application
     * @return void
     */
    public function Execute(): void
    {
        if (count($this->args) == 1) {
            $defaultCmd = $this->GetDefaultCmd();

            if (isset($defaultCmd)) {
                $this->DisplayMessage($defaultCmd->description);
            }
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

    /**
     * GetDefaultCmd return DefaultCmd command.
     * if not found return null
     * @return DefaultCmd
     * @return null
     */
    private function GetDefaultCmd(): ?DefaultCmd
    {
        foreach ($this->commands as $cmd) {
            if ($cmd instanceof DefaultCmd) {
                return $cmd;
            }
        }
    }
}
