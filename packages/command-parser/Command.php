<?php
namespace Packages\CommandParser;

use Closure;

class Command
{
    // the command name.
    public string $use;

    // description about what the command does.
    public string $description;

    // example how to use the command along with
    // values and flags.
    public string $example;

    // run is user-defined function for
    // corresponding command.
    public ?Closure $run = null;

    public function __construct(
        string $use,
        string $description,
        string $example,
    ) {
        $this->use = $use;
        $this->description = $description;
        $this->example = $example;
    }
}

class DefaultCmd
{
    // short description about the app.
    public string $short;

    public string $description;

    public function __construct(string $description, string $short)
    {
        $this->description = $description;
        $this->short = $short;
    }
}
