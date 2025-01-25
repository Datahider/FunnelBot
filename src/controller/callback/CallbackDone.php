<?php

namespace losthost\FunnelBot\controller\callback;

use losthost\telle\abst\AbstractHandlerCallback;
use losthost\telle\Bot;
use losthost\FunnelBot\controller\priority\PriorityIdentity;

class CallbackDone extends AbstractHandlerCallback {
    
    protected function check(\TelegramBot\Api\Types\CallbackQuery &$callback_query): bool {
        
        if ($callback_query->getData() == 'done') {
            return true;
        }
        return false;
    }

    protected function handle(\TelegramBot\Api\Types\CallbackQuery &$callback_query): bool {
        
        $data = Bot::$session->data;
        
        $text = <<<FIN
                Остался последний, но от этого не менее важный шаг.
                
                Чтобы задача попала в мой рабочий процесс, пришлите логотип и название своей компании
                <i>(От этого зависит как я буду видеть вас в своей системе управления задачами)</i>
                    
                Вы можете выслать логотип и название отдельно в любом порядке или отправить изображение и текст в одном сообщении.
                FIN;
        
        Bot::$api->editMessageText(Bot::$chat->id, $callback_query->getMessage()->getMessageId(),
                '✅ Отлично, спасибо!', 'HTML');
        sleep(1);
        Bot::$api->sendMessage(Bot::$chat->id, $text, 'HTML');

        PriorityIdentity::setPriority($data);
        
        try {
            Bot::$api->answerCallbackQuery($callback_query->getId());
        } catch (\Exception $exc) {
            Bot::logException($exc);
        }
        
        return true;
    }
}
