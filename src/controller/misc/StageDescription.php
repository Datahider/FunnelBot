<?php

namespace losthost\FunnelBot\controller\misc;

use losthost\telle\abst\AbstractHandlerMessage;
use losthost\telle\Bot;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;
use losthost\FunnelBot\data\task_data;
use TelegramBot\Api\BotApi;

class StageDescription extends AbstractHandlerMessage {

    protected task_data $task;
    
    protected function check(\TelegramBot\Api\Types\Message &$message): bool {
        return true;
    }

    protected function handle(\TelegramBot\Api\Types\Message &$message): bool {

        global $my_bot;
        
        $this->task = new task_data(['bot_id' => $my_bot->tg_id, 'user_id' => Bot::$user->id], true);
        $messages = $this->task->messages ? $this->task->messages : [];
        $messages[] = $message->getMessageId();
        $this->task->messages = $messages;
        $this->task->write();
        $this->sendGood();
        
        return true;
    }
    
    protected function sendGood() {
        
        global $my_bot;
        
        $api = new BotApi($my_bot->token);
        
        if ($this->task->message_to_delete) {
            try {
                $api->deleteMessage(Bot::$chat->id, $this->task->message_to_delete);
            } catch (\Exception $exc) {
                Bot::logException($exc);
            }
        }
        
        $done_keyboard = new InlineKeyboardMarkup([
            [['text' => '✅ Готово', 'callback_data' => 'done']]
        ]);
        $message = $api->sendMessage(Bot::$chat->id, 'Отлично! Если это всё — нажмите <b>Готово</b>. Или продолжите описание задачи в следующем сообщении', 'HTML', false, null, $done_keyboard);
        $this->task->message_to_delete = $message->getMessageId();
        $this->task->write();
        
    }
}
