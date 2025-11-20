<?php
namespace Faizisyellow\Songslists;

require __DIR__ . "/../vendor/autoload.php";

use Packages\CommandParser\Commander;

$app = new Commander();
$commandFactory = new CommandFactory();

$app->DefaultCmd($commandFactory->CreateDefaultCommand());
$app->AddCmd($commandFactory->CreateAddCommand());
$app->AddCmd($commandFactory->CreateListCommand());
$app->AddCmd($commandFactory->CreateUpdateCommand());
$app->AddCmd($commandFactory->CreateDeleteCommand());

$app->Execute();
