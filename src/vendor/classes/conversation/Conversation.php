<?php

namespace vkbot_conversation\classes\conversation;

use vkbot_conversation\classes\conversation\ConversationData;
use vkbot_conversation\classes\profile\Profile;
use vkbot_conversation\classes\interface\AddedInfo;

require_once(__DIR__ . "/../../Config.php");

trait ConversationInfo
{
    static public function getPeopleConversation(string $peer_id)
    {
        $request_params = [
            'access_token' => TOKEN_VK_CONVERSATION,
            'v' => VERSION_VK_CONVERSATION,
            'peer_id' => $peer_id,
            'fields' => "bdate,sex",
        ];

        $request_url = 'https://api.vk.com/method/messages.getConversationMembers?' . http_build_query($request_params);
        $response = file_get_contents($request_url);

        if ($response !== false) {
            $response_data = json_decode($response, true);
            if (isset($response_data['response'])) {
                return $response_data['response'];
            } else {
                throw new \Exception("$response_data[error][error_msg]");
            }
        } else {
            throw new \Exception("Request error");
        }
    }
}


class Conversation implements AddedInfo
{
    private ConversationData $data;
    private string $filename;
    private string $count;
    private array $profiles = [];

    public function __construct(private string $peer_id, private string $storagePathConversation = "", private string $storagePathProfile = "")
    {
        $this->filename = \BOT_NAME_VK_CONVERSATION[0] . "_cv_" . $peer_id;
        $this->filename = strtolower($this->filename);
        $this->data = new ConversationData($this->filename, $storagePathConversation);
        // $this->getInfoConversationFromVK();
    }

    public function getInfoConversationFromVK()
    {
        $data = ConversationInfo::getPeopleConversation($this->peer_id);
        $this->count = $data['count'];
        foreach ($data['profiles'] as $key => $value) {
            $id = isset($value['id']) ? $value['id'] : "";
            $user = new Profile($id, $this->peer_id, $this->storagePathProfile);
            foreach ($data['items'] as $key => $item) {
                if ($item['member_id'] === $value['id']) {
                    $user->setIsAdmin($item['is_admin'] ? 1 : 0);
                    break;
                }
            }

            isset($value['bdate']) ? $user->setBirthDate($value['bdate']) : $user->setBirthDate('');
            $user->setFirstName(isset($value['first_name']) ? $value['first_name'] : "");
            $user->setLastName(isset($value['last_name']) ? $value['last_name'] : "");

            switch($value['sex']){
                case "2":
                    $user->setSex(1);
                    break;
                case "1":
                    $user->setSex(0);
                    break;
                case "0":
                    $user->setSex(-1);
                    break;
            }
            array_push($this->profiles, $user);
        }
        $this->count = count($this->profiles);
    }

    public function saveInfo(string $key, string | array | object $value)
    {
        $this->data->setKeyValue($key, $value);
    }
    public function getSavedInfo(string $key)
    {
        return $this->data->getKeyValue($key);
    }

    public function clearInfo()
    {
        $this->data->clearData();
    }

    public function getSavedFullInfo()
    {
        return $this->data->getData();
    }

    public function deleteInfo(string $key)
    {
        $data = $this->getSavedFullInfo();
        unset($data->{$key});
        $this->data->setdata($data);
    }

    public function getProfiles()
    {
        $this->getInfoConversationFromVK();
        return $this->profiles;
    }

    public function getProfile(string $idProfile): Profile | null
    {
        foreach ($this->profiles as $value) {
            if ($value->getId() === $idProfile) {
                $pf = new Profile($value->getId(), $this->peer_id, $this->storagePathProfile);
                $pf->setIsAdmin($value->getIsAdmin());
                $pf->setFirstName($value->getFirstName());
                $pf->setLastName($value->getLastName());
                $pf->setSex($value->getSex());
                return $pf;
            }
        }
        return null;
    }

    public function getCount()
    {
        return $this->count;
    }
}
