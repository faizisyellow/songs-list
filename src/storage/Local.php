<?php
namespace Faizisyellow\Songslists\storage;

use Exception;

class Local implements storage
{
    private const array FILE_TYPES = ["json", "toml"];
    private string $filepath;
    private string $filetype;

    public function __construct(string $filepath, string $filetype)
    {
        try {
            $this->ValidateFilePath($filepath);
            $this->ValidateFileType($filetype);
        } catch (Exception $error) {
            echo $error->getMessage() . "\n";
        }

        $this->filepath = $filepath;
        $this->filetype = $filetype;
    }

    private function ValidateFileType(string $filetype): void
    {
        if (!in_array($filetype, self::FILE_TYPES)) {
            throw new Exception("unkown type");
        }
    }

    private function ValidateFilePath(string $filepath): void
    {
        if (!file_exists($filepath)) {
            throw new Exception("file not found");
        }

        if (!($handle = @fopen($filepath, "r+"))) {
            throw new Exception("cannot open file for read/write");
        }
        fclose($handle);
    }

    public function Create(mixed $data): string
    {
        switch ($this->filetype) {
            // json type
            case self::FILE_TYPES[0]:
                $resultId = $this->AddDataToJson($data);
                return $resultId;

            // json is the default
            default:
                $resultId = $this->AddDataToJson($data);
                return $resultId;
        }
    }
    public function Get(): mixed
    {
        switch ($this->filetype) {
            // json type
            case self::FILE_TYPES[0]:
                $data = $this->GetDataFromjson();
                return $data;

            // json is the default
            default:
                $data = $this->GetDataFromjson();
                return $data;
        }
    }
    public function Update(string $id, mixed $data): void {}
    public function Delete(string $id): void {}

    private function AddDataToJson(mixed $data): string
    {
        if (!is_string($data)) {
            throw new Exception("JSON storage only supports string data.");
        }

        $decodedData = $this->GetDataFromjson();

        $id = uniqid();
        $decodedData[] = [
            "id" => $id,
            "content" => $data,
        ];

        $store = fopen($this->filepath, "w");
        fwrite($store, json_encode($decodedData, JSON_PRETTY_PRINT));
        fclose($store);

        return $id;
    }

    private function GetDataFromjson(): array
    {
        $content = file_get_contents($this->filepath);

        $decodedData = json_decode($content, true);

        if (!is_array($decodedData)) {
            $decodedData = [];
        }

        return $decodedData;
    }
}
