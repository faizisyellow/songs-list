<?php
namespace Faizisyellow\Songslists;

use Exception;
use Faizisyellow\Songslists\storage\storage;
use Packages\CommandParser\Command;

class UpdateCommand
{
    private storage $store;
    public function __construct(storage $store)
    {
        $this->store = $store;
    }

    public function build(): Command
    {
        $cmd = new Command(
            "update",
            "update title song",
            "php {file}.php {id} [new title song]",
        );
        $cmd->run = function (array $args) {
            if (count($args) < 1) {
                echo "id and new title is required\n";
                return;
            }

            $id = $args[0];
            $newTitle = $args[1];

            try {
                $this->store->Update($id, $newTitle);
                echo "success update song title to be: $newTitle \n";
            } catch (Exception $error) {
                echo $error->getMessage() . "\n";
            }
        };

        return $cmd;
    }
}
