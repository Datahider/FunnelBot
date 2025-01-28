<?php

namespace losthost\FunnelBot\controller\priority;

use losthost\FunnelBot\controller\priority\AbstractPriorityMessage;
use losthost\FunnelBot\misc\globals;
use losthost\FunnelBot\view\BotAnswer;
use losthost\telle\Bot;

class PioritySetHello extends AbstractPriorityMessage {
    
    protected function check(\TelegramBot\Api\Types\Message &$message): bool {
        static::unsetPriority();
        $text = $message->getText();
        
        if ($text && preg_match("/^\//", $text)) {
            return false;
        }
        
        return true;
    }

    protected function process(\TelegramBot\Api\Types\Message &$message): bool {
        
        $text = $message->getText();
        $entities = $message->getEntities();
        $entities_array = [];
        
        foreach ($entities as $entity) {
            $entities_array[] = json_decode($entity->toJson());
        }
        
        if ($text) {
            globals::$my_bot->hello_data = serialize(['text' => $text, 'entities' => json_encode($entities_array)]);
            globals::$my_bot->write();
            $this->answerOk();
        }
        
        return true;
    }
    
    protected function answerOk() {
    
        BotAnswer::send('sendMessage', [
            'chat_id' => Bot::$chat->id,
            'text' => "Новое приветствие установлено. Для тестирования нажмите /start"
        ]);
        
    }
}
