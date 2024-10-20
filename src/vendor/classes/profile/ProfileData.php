<?php

namespace vkbot_conversation\classes\profile;

use vkbot_conversation\models\DataModel;

class ProfileData extends DataModel
{
    public function __construct(string $storagePath, string $filename) {
        parent::__construct($storagePath, $filename);
    }
}
