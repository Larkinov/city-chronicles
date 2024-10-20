<?php

require_once(__DIR__ . "/autoload.php");

use CityChronicles\Command\CommandInit;
use vkbot_conversation\classes\server\ServerVK;

try {

    $server = new ServerVK("server");
    CommandInit::init($server);

} catch (\Throwable $th) {
    print_r($th);
}
