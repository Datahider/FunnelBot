<?php

namespace losthost\FunnelBot\controller\misc;

use losthost\telle\Bot;
use losthost\telle\abst\AbstractHandlerMessage;

class CheckSetup extends AbstractHandlerMessage {

    protected function check(\TelegramBot\Api\Types\Message &$message): bool {
        $check = new \losthost\FunnelBot\controller\command\CheckSetup();
        return $check->checkUpdate($message);
    }
    
    protected function handle(\TelegramBot\Api\Types\Message &$message): bool {
        $check = new \losthost\FunnelBot\controller\command\CheckSetup();
        return $check->handleUpdate($message);
    }
}
