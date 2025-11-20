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
    public function Update(string $id, mixed $data): void
    {
        if (!is_string($data)) {
            throw new Exception("JSON storage only supports string data.");
        }

        $decodedData = $this->GetDataFromjson();

        $foundData = false;

        // character '&' let the loop references
        // not a copy
        foreach ($decodedData as &$item) {
            if ($item["id"] === $id) {
                $foundData = true;
                $item["content"] = $data;
                break;
            }
        }

        unset($item);

        if (!$foundData) {
            throw new Exception("data with id: $id not found.");
        }

        $result = $this->WriteJsonToStore($decodedData);
        if (is_bool($result)) {
            throw new Exception("failed to update data with id: $id.");
        }
    }

    public function Delete(string $id): void
    {
        $decodedData = $this->GetDataFromjson();

        $foundData = false;
        foreach ($decodedData as $key => $item) {
            if ($item["id"] === $id) {
                $foundData = true;
                unset($decodedData[$key]);
                break;
            }
        }

        if (!$foundData) {
            throw new Exception("data with id: $id not found.");
        }

        $clearData = array_values($decodedData);

        $result = $this->WriteJsonToStore($clearData);
        if (is_bool($result)) {
            throw new Exception("failed to delete data with id: $id.");
        }
    }

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

        $result = $this->WriteJsonToStore($decodedData);

        // if return a boolean, it was error and return false
        // because is_bool check it is bool type, it will return true.
        if (is_bool($result)) {
            throw new Exception("failed to add new data.");
        }

        return $id;
    }

    private function WriteJsonToStore(array $data): int|bool
    {
        $store = fopen($this->filepath, "w");
        $result = fwrite($store, json_encode($data, JSON_PRETTY_PRINT));
        fclose($store);

        return $result;
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
