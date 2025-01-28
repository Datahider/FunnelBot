<?php

namespace losthost\FunnelBot\controller\callback;

use losthost\telle\abst\AbstractHandlerCallback;
use losthost\telle\Bot;
use losthost\FunnelBot\misc\globals;
use losthost\FunnelBot\data\task_data;
use TelegramBot\Api\BotApi;
use losthost\FunnelBot\controller\action\ActionLast;
use losthost\FunnelBot\controller\command\CheckSetup;

class CallbackDone extends AbstractHandlerCallback {
    
    protected task_data $task;
    
    protected function check(\TelegramBot\Api\Types\CallbackQuery &$callback_query): bool {
        
        if ($callback_query->getData() == 'done') {
            return true;
        }
        return false;
    }

    protected function handle(\TelegramBot\Api\Types\CallbackQuery &$callback_query): bool {
        
        $check = new CheckSetup();
        $message = $callback_query->getMessage();
        if ($check->checkUpdate($message)) {
            $check->handleUpdate($message);
        } else {
            $this->process($callback_query);
        }

        try {
            $api = new BotApi(globals::$my_bot->token);
            $api->answerCallbackQuery($callback_query->getId());
        } catch (\Exception $exc) {
            Bot::logException($exc);
        }
        
        return true;
    }

    protected function process(\TelegramBot\Api\Types\CallbackQuery &$callback_query) {
        
        $this->task = new task_data(['bot_id' => globals::$my_bot->tg_id, 'user_id' => Bot::$user->id], true);

        if (empty($this->task->company_logo) || empty($this->task->company_name)) {
            $this->sendGiveIdentity($callback_query->getMessage()->getMessageId());
            $this->task->stage = 'identity';
        } else {
            $this->task->stage = 'done';
            ActionLast::do();
        }
        
        $this->task->write();
    }
    
    protected function sendGiveIdentity(int $edit_message_id) {
        
        $text = <<<FIN
                Остался последний, но от этого не менее важный шаг.
                
                Чтобы задача попала в мой рабочий процесс, пришлите логотип и название своей компании
                <i>(От этого зависит как я буду видеть вас в своей системе управления задачами)</i>
                    
                Вы можете выслать логотип и название отдельно в любом порядке или отправить изображение и текст в одном сообщении.
                FIN;

        $api = new BotApi(globals::$my_bot->token);
        $api->editMessageText(Bot::$chat->id, $edit_message_id,
                '✅ Отлично, спасибо!', 'HTML');
        sleep(1);
        $api->sendMessage(Bot::$chat->id, $text, 'HTML');
    }
}
