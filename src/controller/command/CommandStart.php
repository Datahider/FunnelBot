<?php

namespace losthost\FunnelBot\controller\command;

use losthost\telle\abst\AbstractHandlerCommand;
use losthost\telle\Bot;
use losthost\FunnelBot\view\AnswerAlreadyDone;
use losthost\FunnelBot\data\task_data;
use losthost\FunnelBot\controller\action\ActionSendHello;
use TelegramBot\Api\BotApi;

class CommandStart extends AbstractHandlerCommand {
    
    const COMMAND = 'start';
    
    protected function check(\TelegramBot\Api\Types\Message &$message): bool {
        Bot::logComment('checking in /start');
        $result = parent::check($message);
        Bot::logComment('checked in start: '. $result);
        return $result;
    }
    protected function handle(\TelegramBot\Api\Types\Message &$message): bool {

        global $my_bot;

        $task_data = new task_data(['bot_id' => $my_bot->tg_id, 'user_id' => Bot::$user->id], true);
        if ($task_data->stage == 'done') {
            AnswerAlreadyDone::do($task_data->group_link);
            return true;
        }
        
        $task_data->subject = null;
        $task_data->messages = null;
        $task_data->message_to_delete = null;
        
        if ($my_bot->task_subject) {
            ActionSendHello::do();
            $task_data->subject = $my_bot->task_subject;
            $task_data->stage = 'description';
        } else {
            $task_data->stage = 'subject';
            ActionSendHello::do();
        }

        $task_data->write();
        
        if ($my_bot->admin_id == Bot::$user->id) {
            $this->helloAdmin();
        }
        
        return true;
    }
    
    protected function helloAdmin() {
    
        global $my_bot;
        
        if ($my_bot->task_subject) {
            $subject_info = "Тема задачи по умолчанию: <b>$my_bot->task_subject</b>";
        } else {
            $subject_info = "Тема задачи по умолчанию не установлена.";
        }
        
        $text = <<<FIN
                ☝️ Таким сообщением я буду приветствовать ваших пользователей. Для его изменения нажмите /sethello
                
                $subject_info
                
                Используйте /setsubject для установки или сброса темы задачи по умолчанию.
                
                <i>Для проверки моей работы, вы можете продолжать ввод задачи, как будто вы обычный пользователь.</i>
                FIN;

        sleep(1);
        $api = new BotApi($my_bot->token);
        $api->sendMessage(Bot::$chat->id, $text, 'HTML');
    }
    
}
