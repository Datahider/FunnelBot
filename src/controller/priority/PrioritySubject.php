<?php

namespace losthost\FunnelBot\controller\priority;

use losthost\telle\abst\AbstractHandlerMessage;
use losthost\telle\Bot;
use losthost\FunnelBot\controller\priority\PriorityDescription;

class PrioritySubject extends AbstractHandlerMessage {
    
    protected function check(\TelegramBot\Api\Types\Message &$message): bool {
        $text = $message->getText();
        if ($text && preg_match("/^\//", $text)) {
            self::unsetPriority();
            return false;
        }
        return true;
    }

    protected function handle(\TelegramBot\Api\Types\Message &$message): bool {
        
        $text = $message->getText();
        
        if (!$text) {
            $this->sendEmpty();
        } elseif (mb_strlen($text) > 128) {
            $this->sendTooLong();
        } else {
            $this->process($text);
        }
        
        return true;
    }
    
    protected function sendEmpty() {
        Bot::$api->sendMessage(Bot::$chat->id, 'Пожалуйста, пришлите название задачи в виде текста. После этого вы сможете отправлять медиафайлы.');
    }

    protected function sendTooLong() {
        Bot::$api->sendMessage(Bot::$chat->id, 'Пожалуйста, пришлите кратное название задачи. Затем вы сможете отправить детальное описание и/или медиафайл(ы).');
    }
    
    protected function process(string $text) {
        $data['subject'] = $text;
        
        Bot::$api->sendMessage(Bot::$chat->id, 'Ок. Теперь более подробно опишите суть задачи. При необходимости приложите изображения, видео или другием медиа.');
        PriorityDescription::setPriority($data);
    }
}
