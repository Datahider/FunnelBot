<?php

namespace losthost\FunnelBot\controller\priority;

use losthost\telle\abst\AbstractHandlerMessage;
use losthost\telle\Bot;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;

class PriorityDescription extends AbstractHandlerMessage {

    protected function check(\TelegramBot\Api\Types\Message &$message): bool {
        $text = $message->getText();
        if ($text && preg_match("/^\//", $text)) {
            self::unsetPriority();
            return false;
        }
        return true;
    }

    protected function handle(\TelegramBot\Api\Types\Message &$message): bool {

        $data = Bot::$session->data;
        
        if (!isset($data['messages'])) {
            $data['messages'] = [];
        }
        
        $data['messages'][] = $message->getMessageId();
        Bot::$session->set('data', $data);
        
        $this->sendGood();
        
        return true;
    }
    
    protected function sendGood() {
        
        if (Bot::$session->state) {
            try {
                Bot::$api->deleteMessage(Bot::$chat->id, Bot::$session->state);
            } catch (\Exception $exc) {
                Bot::logException($exc);
            }
        }
        
        $done_keyboard = new InlineKeyboardMarkup([
            [['text' => '✅ Готово', 'callback_data' => 'done']]
        ]);
        $message = Bot::$api->sendMessage(Bot::$chat->id, 'Отлично! Если это всё — нажмите <b>Готово</b>. Или продолжите описание задачи в следующем сообщении', 'HTML', false, null, $done_keyboard);
        Bot::$session->set('state', $message->getMessageId());
    }
}
