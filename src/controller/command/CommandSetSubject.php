<?php

namespace losthost\FunnelBot\controller\command;

use losthost\telle\abst\AbstractHandlerCommand;
use losthost\telle\Bot;
use losthost\telle\model\DBBotParam;

class CommandSetSubject extends AbstractHandlerCommand {
    
    const COMMAND = 'setsubject';
    
    protected function handle(\TelegramBot\Api\Types\Message &$message): bool { 
        
        $subject_param_name = Bot::getMe()->getUsername(). '_subject';
        $subject_param = new DBBotParam($subject_param_name, null);
        
        if ($this->args) {
            $subject_param->value = $this->args;
            Bot::$api->sendMessage(Bot::$chat->id, "Установлена тема новой задачи по умолчанию:\n\n<b>$subject_param->value</b>\n\nБот не будет запрашивать тему задачи у пользователя", 'HTML');
        } else {
            $subject_param->value = null;
            Bot::$api->sendMessage(Bot::$chat->id, "Тема новой задачи по умолчанию сброшена.\n\nБот будет запрашивать тему задачи у пользователя");
        }
        
        return true;
    }

}
