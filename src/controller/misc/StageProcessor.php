<?php

namespace losthost\FunnelBot\controller\misc;

use losthost\telle\abst\AbstractHandlerMessage;
use losthost\FunnelBot\data\task_data;
use losthost\telle\Bot;
use losthost\FunnelBot\view\BotAnswer;
use losthost\FunnelBot\view\AnswerAlreadyDone;

use losthost\FunnelBot\controller\misc\StageSubject;
use losthost\FunnelBot\controller\misc\StageDescription;
use losthost\FunnelBot\controller\misc\StageIdentity;

class StageProcessor extends AbstractHandlerMessage {
    
    protected task_data $task;
    
    protected function check(\TelegramBot\Api\Types\Message &$message): bool {
        
        global $my_bot;
        
        $this->task = new task_data(['bot_id' => $my_bot->tg_id, 'user_id' => Bot::$user->id], true);
        if ($this->task->stage) {
            return true;
        }
        
        return false;
    }

    protected function handle(\TelegramBot\Api\Types\Message &$message): bool {
        
        switch ($this->task->stage) {
            case 'subject':
                $handler = new StageSubject();
                return $handler->handleUpdate($message);
            case 'description': 
                $handler = new StageDescription();
                return $handler->handleUpdate($message);
            case 'identity': 
                $handler = new StageIdentity();
                return $handler->handleUpdate($message);
            case 'done': 
                AnswerAlreadyDone::do($this->task->group_link);
                return true;
            default:
                BotAnswer::send('sendMessage', [
                    'chat_id' => Bot::$chat->id,
                    'text' => 'Для начала работы нажмите /start'
                ]);
                return true;
        }
        
        return false;
    }
}
