<?php
namespace Faizisyellow\Songslists;

require __DIR__ . "/../vendor/autoload.php";

use Packages\CommandParser\Commander;

$app = new Commander();
$app->DefaultCmd(DefaultCommand::build());
$app->Execute();
