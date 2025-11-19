<?php
namespace Packages\CommandParser;

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
