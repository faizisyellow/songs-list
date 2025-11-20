<?php
namespace Packages\CommandParser;

use Exception;
use InvalidArgumentException;
use RuntimeException;

class Commander
{
    private array $args;

    /** @var Command[] */
    private array $commands = [];

    private ?DefaultCmd $defCommand = null;

    public function __construct()
    {
        $this->args = $GLOBALS["argv"];
    }

    /**
     * AddCmd adds new cmd handler to collection of commands.
     * if new cmd handler is the same with existing one, it'll calls the first cmd handler.
     * @param Command $cmd
     * @return void
     * @throws InvalidArgumentException
     * */
    public function AddCmd(Command $cmd): void
    {
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
        try {
            $this->ExecuteE();
        } catch (Exception $error) {
            $this->DisplayMessage($error->getMessage());
        }
    }

    /**
     * Execute calls user-define function to run
     * the application with throw an error.
     * @return void
     */
    private function ExecuteE(): void
    {
        if (count($this->args) <= 1) {
            $this->RunDefaultCmd();
            return;
        }

        $commandRequest = $this->args[1] ?? "";

        $isFound = $this->FindCmdHandler($commandRequest);

        if ($isFound) {
            $cmd = $this->GetCmdHandler($commandRequest);

            // file name and command name will not included.
            $clearArgs = array_slice($this->args, 2);

            $this->RunCmd($cmd, $clearArgs);
        } else {
            $this->RunDefaultCmd();
        }
    }

    /**
     * RunCmd run the associate command.
     * @param Command $cmd the command handler
     * @param array $args the arguments from command line
     * @return void
     * @throws \Exception
     * @throws RuntimeException
     */
    private function RunCmd(Command $cmd, array $args): void
    {
        if (in_array("--help", $args)) {
            $this->DisplayCommandInformation($cmd->description, $cmd->example);
            return;
        }

        if (!is_callable($cmd->run)) {
            throw new RuntimeException("command has no valid run() callback");
        }

        ($cmd->run)($args);
    }

    /**
     * RunCmd run the associate command.
     * @param Command $cmd the command handler
     * @param array $args the arguments from command line
     * @return void
     * @throws \Exception
     * @throws RuntimeException
     */
    private function DisplayCommandInformation(
        string $description,
        string $example,
    ): void {
        $msg = "{$description}\n\n";
        $msg .= "Example: {$example}\n";

        $this->DisplayMessage($msg);
    }

    /**
     * FindCmdHandler finds the command object with the same command string passed.
     * if found in the collection of commands it will return true for the first command
     * @param string $command
     * @return bool
     */
    private function FindCmdHandler(string $command): bool
    {
        $found = false;

        foreach ($this->commands as $cmd) {
            if ($cmd->use === $command) {
                return $found = true;
            }
        }

        return $found;
    }

    /**
     * GetCmdHandler get the command object with the same command string passed.
     * if found in the collection of commands it will return the first command
     * @param string $command
     * @return Command|null
     */
    private function GetCmdHandler(string $command): ?Command
    {
        foreach ($this->commands as $cmd) {
            if ($cmd->use === $command) {
                return $cmd;
            }
        }

        return null;
    }

    /**
     * DisplayMessage shows message to the command line.
     * @param string $msg
     * @return void
     */
    private function DisplayMessage(string $msg): void
    {
        echo "$msg \n";
    }

    /**
     * RunDefaultCmd run the defaultCmd that already register when there's no commands.
     * @return void
     */
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
        } else {
            throw new Exception("default command not found");
        }
    }
}
