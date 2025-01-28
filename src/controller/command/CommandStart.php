<?php

namespace losthost\FunnelBot\controller\command;

use losthost\telle\abst\AbstractHandlerCommand;
use losthost\telle\Bot;
use losthost\FunnelBot\view\AnswerAlreadyDone;
use losthost\FunnelBot\data\task_data;
use losthost\FunnelBot\controller\action\ActionSendHello;
use TelegramBot\Api\BotApi;
use losthost\DB\DB;
use losthost\FunnelBot\misc\globals;
use losthost\FunnelBot\view\AnswerAlreadyHasGroup;

class CommandStart extends AbstractHandlerCommand {
    
    const COMMAND = 'start';
    
    protected function handle(\TelegramBot\Api\Types\Message &$message): bool {
        
        if (globals::isAdmin()) {
            ActionSendHello::do();
            $this->helloAdmin();
            return true;
        } elseif (globals::hasGroup()) {
            AnswerAlreadyHasGroup::do();
            return true;
        }

        $task_data = new task_data(['bot_id' => globals::$my_bot->tg_id, 'user_id' => Bot::$user->id], true);
        $this->clearTask($task_data);
        
        if (globals::$my_bot->task_subject) {
            $task_data->subject = globals::$my_bot->task_subject;
            $task_data->stage = 'description';
            ActionSendHello::do();
        } else {
            $task_data->stage = 'subject';
            ActionSendHello::do();
        }

        $task_data->write();
        
        return true;
    }
    
    protected function clearTask(task_data &$task_data) {
        
        $task_data->subject = null;
        $task_data->message_to_delete = null;
        $task_data->stage = null;
        $sth = DB::prepare('DELETE FROM [message_data] WHERE task_id=?');
        $sth->execute([$task_data->id]);
        $task_data->write();
        
    }
    
    protected function helloAdmin() {
    
        if (globals::$my_bot->task_subject) {
            $subject = globals::$my_bot->task_subject;
            $subject_info = "Тема задачи по умолчанию: <b>$subject</b>";
        } else {
            $subject_info = "Тема задачи по умолчанию не установлена. Первое сообщение пользователя будет считаться темой задачи.";
        }
        
        $text = <<<FIN
                ☝️ Таким сообщением я буду приветствовать ваших пользователей. Для его изменения нажмите /sethello
                
                $subject_info
                
                Используйте /setsubject для установки или сброса темы задачи по умолчанию.
                FIN;

        sleep(1);
        $api = new BotApi(globals::$my_bot->token);
        $api->sendMessage(Bot::$chat->id, $text, 'HTML');
    }
    
}
