<?php

namespace vkbot_conversation\classes\profile;

use vkbot_conversation\classes\interface\AddedInfo;
use vkbot_conversation\classes\profile\ProfileData;

class Profile implements AddedInfo
{
    private string $first_name;
    private string $last_name;
    private int $sex;
    private int $isAdmin;
    private string $filename;
    private string $birthDate;
    private ProfileData $data;

    public function __construct(private string $id, private string $peer_id, private string $storagePath="") {
        $this->filename=\BOT_NAME_VK_CONVERSATION[0] ."_cv_". $peer_id . "_pf_$id";
        $this->filename = strtolower($this->filename);
        $this->data = new ProfileData($storagePath,$this->filename);
    }

    public function setId(string $id)
    {
        $this->id = $id;
    }
    public function getId(): string
    {
        return $this->id;
    }
    public function setFirstName(string $first_name)
    {
        $this->first_name = $first_name;
    }
    public function getFirstName(): string
    {
        return $this->first_name;
    }
    public function setLastName(string $last_name)
    {
        $this->last_name = $last_name;
    }
    public function getLastName(): string
    {
        return $this->last_name;
    }

    public function setBirthDate(string $birthDate)
    {
        $this->birthDate = $birthDate;
    }
    public function getBirthDate(): string
    {
        return $this->birthDate;
    }
    public function setSex(int $sex)
    {
        $this->sex = $sex;
    }
    public function getSex(): int
    {
        return $this->sex;
    }
    public function setIsAdmin(int $isAdmin)
    {
        $this->isAdmin = $isAdmin;
    }
    public function getIsAdmin(): int
    {
        return $this->isAdmin;
    }

    public function saveInfo(string $key, string | array | object $value){
        $this->data->setKeyValue($key, $value);
    }
    public function getSavedInfo(string $key){
        return $this->data->getKeyValue($key);
    }

    public function clearInfo(){
        $this->data->clearData();
    }

    public function getSavedFullInfo(){
        return $this->data->getData();
    }

    public function deleteInfo(string $key){
        $data = $this->getSavedFullInfo();
        unset($data->{$key}); 
        $this->data->setdata($data);
    }


}
