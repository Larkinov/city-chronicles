<?php

namespace vkbot_conversation\classes\server;

use vkbot_conversation\models\DataModel;

class ServerData extends DataModel
{
    private string $key;
    private string $urlServer;
    private string $ts;

    public function __construct(string $filename, string $storagePathServer)
    {
        parent::__construct($storagePathServer,$filename);
        $this->pathStorage .= $storagePathServer; 

        if ($this->hasData()) {
            $data = $this->getData();
            $this->key = $data->key;
            $this->urlServer = $data->server;
            $this->ts = $data->ts;
        }
    }

    public function setKey(string $key)
    {
        $this->setKeyValue("key", $key);
        $this->key = $key;
    }

    public function setUrlServer(string $urlServer)
    {
        $this->setKeyValue("server", $urlServer);
        $this->urlServer = $urlServer;
    }

    public function setTs(string $ts)
    {
        $this->setKeyValue("ts", $ts);
        $this->ts = $ts;
    }

    public function getKey()
    {
        return $this->key;
    }

    public function getUrlServer()
    {
        return $this->urlServer;
    }

    public function getTs()
    {
        return $this->ts;
    }

    public function setData(object $data)
    {
        parent::setData($data);
        $this->key = $data->key;
        $this->urlServer = $data->server;
        $this->ts = $data->ts;
    }
}
