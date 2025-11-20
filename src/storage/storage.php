<?php
namespace Faizisyellow\Songslists\storage;

interface storage
{
    public function Create(mixed $data): string;
    public function Get(): mixed;
    public function Update(string $id, mixed $data): void;
    public function Delete(string $id): void;
}
