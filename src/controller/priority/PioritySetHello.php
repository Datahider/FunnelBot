<?php

namespace losthost\FunnelBot\controller\priority;

use losthost\telle\abst\AbstractHandlerMessage;
use losthost\telle\Bot;
use losthost\telle\model\DBBotParam;
use losthost\FunnelBot\controller\action\ActionSendHello;

class PioritySetHello extends AbstractHandlerMessage {
    
    protected function check(\TelegramBot\Api\Types\Message &$message): bool {
        static::unsetPriority();
        $text = $message->getText();
        
        if ($text && preg_match("/^\//", $text)) {
            return false;
        }
        
        return true;
    }

    protected function handle(\TelegramBot\Api\Types\Message &$message): bool {
        
        $text = $message->getText();
        $entities = $message->getEntities();
        $entities_array = [];
        
        foreach ($entities as $entity) {
            $entities_array[] = json_decode($entity->toJson());
        }
        
        if ($text) {
            $hello_param_name = Bot::getMe()->getUsername(). '_hello';
            $hello_param = new DBBotParam($hello_param_name, null);
            
            $hello_param->value = serialize(['text' => $text, 'entities' => json_encode($entities_array)]);
            
            ActionSendHello::do();
        }
        
        return true;
    }
}
