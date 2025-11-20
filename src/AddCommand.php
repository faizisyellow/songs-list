<?php
namespace Faizisyellow\Songslists;

use Exception;
use Faizisyellow\Songslists\storage\storage;
use Packages\CommandParser\Command;

class AddCommand
{
    private storage $store;
    public function __construct(storage $store)
    {
        $this->store = $store;
    }

    public function build(): Command
    {
        $cmd = new Command(
            "add",
            "add new song to the collection",
            "php {file}.php add [song name]",
        );

        $cmd->run = function (array $args) {
            if (count($args) === 0) {
                echo "empty song title\n";
                return;
            }

            $title = $args[0];

            try {
                $result = $this->store->Create($title);
                echo "success add new song with id : $result\n";
            } catch (Exception $error) {
                echo $error->getMessage();
            }
        };

        return $cmd;
    }
}
