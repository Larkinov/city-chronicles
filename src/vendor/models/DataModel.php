<?php

namespace vkbot_conversation\models;

use stdClass;
use vkbot_conversation\utils\FileWorker;
use vkbot_conversation\utils\Converter;

require_once(__DIR__ . "/../Config.php");

abstract class DataModel
{

    public function __construct(
        protected string $pathStorage,
        protected string $filename
    ) {
        $this->pathStorage = ROOT_PATH_STORAGE_VK_CONVERSATION . $pathStorage;
    }

    public function hasData(): bool
    {
        $data = FileWorker::readData($this->pathStorage . "/" . $this->filename);
        if (empty($data)) {
            $this->createFile($this->pathStorage . "/" . $this->filename);
            return false;
        } else
            return true;
    }

    public function getData(): object|null
    {
        $data = FileWorker::readData($this->pathStorage . "/" . $this->filename);
        if (!empty($data))
            return $data;
        else
            return null;
    }

    public function setData(object $data)
    {
        FileWorker::writeData($this->pathStorage . "/" . $this->filename, $data);
    }

    public function clearData(){
        FileWorker::clearFile($this->pathStorage . "/" . $this->filename);
    }

    public function setKeyValue(string $key, string|array|object $value)
    {
        if ($this->hasData()) {
            $data = $this->getData();
            if (is_string($value) || is_object($value))
                $data->{$key} = $value;
            if (is_array($value))
                $data->{$key} = Converter::arrayToObject($value);
            FileWorker::writeData($this->pathStorage . "/" . $this->filename, $data);
        } else {
            if (is_string($value))
                $data = Converter::arrayToObject([$key => $value]);
            if (is_array($value))
                $data = Converter::arrayToObject($value);
            FileWorker::writeData($this->pathStorage . "/" . $this->filename, $data);
        }
    }

    public function getKeyValue(string $key)
    {
        if ($this->hasData()) {
            $data = $this->getData();
            return $data->$key;
        } else
            return null;
    }

    public function deleteData()
    {
        file_put_contents($this->pathStorage . "/" . $this->filename, "");
    }

    private function createFile(string $filename)
    {
        FileWorker::createEmptyFile($filename);
    }
}
