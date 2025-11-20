<?php
namespace Faizisyellow\Songslists;

use Exception;
use Faizisyellow\Songslists\storage\storage;
use Packages\CommandParser\Command;

class ListCommand
{
    private storage $store;

    public function __construct(storage $store)
    {
        $this->store = $store;
    }

    public function build(): Command
    {
        $listCmd = new Command(
            "list",
            "list all songs",
            "php {file}.php list [args]",
        );

        $listCmd->run = function (array $args) {
            try {
                $songs = $this->store->Get();

                if (count($songs) === 0) {
                    echo "empty songs";
                    return;
                }

                $displaySongs = "";
                foreach ($songs as $song) {
                    $displaySongs .= "{$song["id"]}\t";
                    $displaySongs .= "{$song["content"]}\n";
                }

                echo $displaySongs;
            } catch (Exception $error) {
                echo $error->getMessage();
            }
        };

        return $listCmd;
    }
}
