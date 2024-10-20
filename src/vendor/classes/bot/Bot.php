<?php

namespace vkbot_conversation\classes\bot;

require_once(__DIR__."/../../Config.php");

class Bot
{

    public function sendMessage(string $text, string $peer_id)
    {
        try {
            $request_params = [
                'access_token' => TOKEN_VK_CONVERSATION,
                'v' => VERSION_VK_CONVERSATION,
                'message' => $text,
                'random_id' => '0',
                'peer_id' => $peer_id,
            ];

            $request_url = 'https://api.vk.com/method/messages.send?' . http_build_query($request_params);
            $response = file_get_contents($request_url);
            if ($response !== false) {
                $response_data = json_decode($response, true);
                if (isset($response_data['response'])) {
                    if (isset($response_data['response']['key']) && isset($response_data['response']['server']) && isset($response_data['response']['ts'])) {
                        return $response_data['response'];
                    }
                } else {
                    throw new \Exception("$response_data[error][error_msg]");
                }
            } else {
                throw new \Exception("error send message - $response");
            }
            return false;
        } catch (\Throwable $th) {
            print_r($th);
        }
    }
}
