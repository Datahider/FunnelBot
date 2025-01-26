<?php

namespace losthost\FunnelBot\controller\priority;

use losthost\telle\abst\AbstractHandlerMessage;
use losthost\FunnelBot\controller\command\CheckSetup;

abstract class AbstractPriorityMessage extends AbstractHandlerMessage {
    
    protected function handle(\TelegramBot\Api\Types\Message &$message): bool {

        $check = new CheckSetup();
        if ($check->checkUpdate($message)) {
            return $check->handleUpdate($message);
        }
        
        return $this->process($message);
    }
    
    abstract protected function process(\TelegramBot\Api\Types\Message &$message) : bool;
}
