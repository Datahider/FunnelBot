<?php

namespace losthost\FunnelBot\controller\misc;

use losthost\telle\abst\AbstractHandlerMessage;
use losthost\telle\Bot;
use losthost\FunnelBot\controller\action\ActionLast;
use losthost\FunnelBot\data\task_data;

class StageIdentity extends AbstractHandlerMessage {
    
    protected task_data $task;
    
    protected function check(\TelegramBot\Api\Types\Message &$message): bool {
        return true;
    }

    protected function handle(\TelegramBot\Api\Types\Message &$message): bool {

        global $my_bot;
        $this->task = new task_data(['bot_id' => $my_bot->tg_id, 'user_id' => Bot::$user->id], true);
        
        if ($message->getText()) {
            $this->task->company_name = $message->getText();
        } elseif ($message->getPhoto()) {
            if ($message->getCaption()) {
                $this->task->company_name = $message->getCaption();
            }
            $size = 0;
            foreach ($message->getPhoto() as $photo) {
                if ($photo->getFileSize() > $size) {
                    $this->task->company_logo = $photo->getFileId();
                    $size = $photo->getFileSize();
                }
            }
        }
        
        $this->task->isModified() && $this->task->write();

        if ($this->task->company_name && $this->task->company_logo) {
            $this->task->stage = 'done';
            $this->task->write();
            ActionLast::do();
        }
        
        return true;
    }
}
