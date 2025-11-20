<?php
namespace Faizisyellow\Songslists;

use Exception;
use Faizisyellow\Songslists\storage\storage;
use Packages\CommandParser\Command;

class DeleteCommand
{
    private storage $store;
    public function __construct(storage $store)
    {
        $this->store = $store;
    }

    public function build(): Command
    {
        $cmd = new Command(
            "delete",
            "delete song with id",
            "php {file}.php delete [id]",
        );

        $cmd->run = function (array $args) {
            if (count($args) === 0) {
                echo "id is required\n";
                return;
            }

            $id = $args[0];
            try {
                $this->store->Delete($id);
                echo "success delete song with id : $id\n";
            } catch (Exception $error) {
                echo $error->getMessage() . "\n";
            }
        };

        return $cmd;
    }
}
