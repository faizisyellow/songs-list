<?php
namespace Faizisyellow\Songslists;

use Packages\CommandParser\DefaultCmd;

class DefaultCommand
{
    public static function build(): DefaultCmd
    {
        return new DefaultCmd(
            "songs list is command line to manage songs",
            "manage songs via CLI",
        );
    }
}
