<?php

namespace losthost\FunnelBot\controller\misc;

use losthost\telle\abst\AbstractHandlerMessage;
use losthost\telle\Bot;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;
use losthost\FunnelBot\data\task_data;
use TelegramBot\Api\BotApi;
use losthost\FunnelBot\data\message_data;
use losthost\FunnelBot\misc\globals;

class StageDescription extends AbstractHandlerMessage {

    protected task_data $task;
    
    protected function check(\TelegramBot\Api\Types\Message &$message): bool {
        return true;
    }

    protected function handle(\TelegramBot\Api\Types\Message &$message): bool {

        $this->task = new task_data(['bot_id' => globals::$my_bot->tg_id, 'user_id' => Bot::$user->id], true);
        $stored_message = new message_data();
        $stored_message->task_id = $this->task->id;
        $stored_message->message  = $message;
        $stored_message->write();
        $this->sendGood();
        
        return true;
    }
    
    protected function sendGood() {
        
        $api = new BotApi(globals::$my_bot->token);
        
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
