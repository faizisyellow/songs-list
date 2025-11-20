<?php
namespace Faizisyellow\Songslists;

use Packages\CommandParser\Command;

class ListCommand
{
    public static function build(): Command
    {
        $listCmd = new Command(
            "list",
            "list all songs",
            "php {file}.php list [args]",
        );
        $listCmd->run = function (array $args) {
            echo "list called";
        };

        return $listCmd;
    }
}
