<?php

namespace losthost\FunnelBot\controller\command;

use losthost\telle\abst\AbstractHandlerCommand;
use losthost\telle\Bot;
use losthost\telle\model\DBBotParam;
use losthost\FunnelBot\view\BotAnswer;

class CommandSetSubject extends AbstractHandlerCommand {
    
    const COMMAND = 'setsubject';
    
    protected function handle(\TelegramBot\Api\Types\Message &$message): bool { 
        
        global $my_bot;
        
        if ($my_bot->admin_id == Bot::$user->id) {
            $this->process();
        } else {
            Bot::logComment('You are not admin. Admin is: '. $my_bot->admin_id);
        }
        
        return true;
    }

    protected function process() {
        
        global $my_bot;
        
        if ($this->args) {
            $my_bot->task_subject = $this->args;
            $my_bot->write();
            BotAnswer::send('sendMessage', [
                'chat_id' => Bot::$chat->id,
                'text' => "Установлена тема новой задачи по умолчанию:\n\n<b>$my_bot->task_subject</b>\n\nБот не будет запрашивать тему задачи у пользователя",
                'parse_mode' => 'HTML'
            ]);
        } else {
            $my_bot->task_subject = null;
            $my_bot->write();
            BotAnswer::send('sendMessage', [
                'chat_id' => Bot::$chat->id,
                'text' => "Тема новой задачи по умолчанию сброшена.\n\nБот будет запрашивать тему задачи у пользователя",
            ]);
        }
        
    }
}
