<?php

namespace vkbot_conversation\classes\interface;

interface AddedInfo {
    public function saveInfo(string $key, string | array | object $value);
    public function getSavedInfo(string $key);
    public function clearInfo();
    public function getSavedFullInfo();
    public function deleteInfo(string $key);
}
