<?php

namespace losthost\FunnelBot\controller\misc;

use losthost\telle\Bot;
use losthost\FunnelBot\data\task_data;
use losthost\FunnelBot\view\BotAnswer;
use losthost\telle\abst\AbstractHandlerMessage;

class StageSubject extends AbstractHandlerMessage {

    protected task_data $task;
    
    protected function check(\TelegramBot\Api\Types\Message &$message): bool {
        return true;
    }

    protected function sendEmpty() {
        BotAnswer::send('sendMessage', [
            'chat_id' => Bot::$chat->id,
            'text' => 'Пожалуйста, пришлите название задачи в виде текста. После этого вы сможете отправлять медиафайлы.'
        ]);
    }

    protected function sendTooLong() {
        BotAnswer::send('sendMessage', [
            'chat_id' => Bot::$chat->id,
            'text' => 'Пожалуйста, пришлите краткое название задачи. Затем вы сможете отправить детальное описание и/или медиафайл(ы).'
        ]);
    }
    
    protected function handle(\TelegramBot\Api\Types\Message &$message) : bool {

        global $my_bot;
        
        $text = $message->getText();
        
        if (!$text) {
            $this->sendEmpty();
            return true;
        } elseif (mb_strlen($text) > 128) {
            $this->sendTooLong();
            return true;
        }
        
        $this->task = new task_data(['bot_id' => $my_bot->tg_id, 'user_id' => Bot::$user->id], true);
        $this->task->subject = $text;
        $this->task->stage = 'description';
        $this->task->write();
        
        Bot::logComment('Got subject');
        BotAnswer::send('sendMessage', [
            'chat_id' => Bot::$chat->id,
            'text' => 'Ок. Теперь более подробно опишите суть задачи. При необходимости приложите изображения, видео или другие медиа.'
        ]);
        
        return true;
    }
}
