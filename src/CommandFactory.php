<?php
namespace Faizisyellow\Songslists;

use Faizisyellow\Songslists\storage\Local;
use Faizisyellow\Songslists\storage\storage;
use Packages\CommandParser\Command;
use Packages\CommandParser\DefaultCmd;

class CommandFactory
{
    private storage $store;

    public function __construct()
    {
        $storepath = dirname(__DIR__, 1) . "/.config/songs_list.json";
        $this->store = new Local($storepath, "json");
    }

    public function CreateDefaultCommand(): DefaultCmd
    {
        return DefaultCommand::build();
    }

    public function CreateListCommand(): Command
    {
        return new ListCommand($this->store)->build();
    }

    public function CreateAddCommand(): Command
    {
        return new AddCommand($this->store)->build();
    }

    public function CreateDeleteCommand(): Command
    {
        return new DeleteCommand($this->store)->build();
    }

    public function CreateUpdateCommand(): Command
    {
        return new UpdateCommand($this->store)->build();
    }
}
