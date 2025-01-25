<?php

namespace losthost\FunnelBot\controller\priority;

use losthost\telle\abst\AbstractHandlerMessage;
use losthost\telle\Bot;
use losthost\FunnelBot\controller\action\ActionLast;

class PriorityIdentity extends AbstractHandlerMessage {
    
    protected function check(\TelegramBot\Api\Types\Message &$message): bool {
        if ($message->getPhoto() || $message->getText()) {
            return true;
        }
    }

    protected function handle(\TelegramBot\Api\Types\Message &$message): bool {
        $data = Bot::$session->data;
        
        if ($message->getText()) {
            $data['name'] = $message->getText();
        } elseif ($message->getPhoto()) {
            if ($message->getCaption()) {
                $data['name'] = $message->getCaption();
            }
            $size = 0;
            foreach ($message->getPhoto() as $photo) {
                if ($photo->getFileSize() > $size) {
                    $data['logo'] = $photo->getFileId();
                    $size = $photo->getFileSize();
                }
            }
        }
        
        Bot::$session->set('data', $data);

        if (!empty($data['name'] && !empty($data['logo']))) {
            static::unsetPriority();
            ActionLast::do();
        }
        
        return true;
    }
}
