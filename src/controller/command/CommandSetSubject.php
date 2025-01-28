<?php

namespace losthost\FunnelBot\controller\command;

use losthost\telle\abst\AbstractHandlerCommand;
use losthost\telle\Bot;
use losthost\telle\model\DBBotParam;
use losthost\FunnelBot\view\BotAnswer;
use losthost\FunnelBot\misc\globals;

class CommandSetSubject extends AbstractHandlerCommand {
    
    const COMMAND = 'setsubject';
    
    protected function handle(\TelegramBot\Api\Types\Message &$message): bool { 
        
        if (globals::isAdmin()) {
            $this->process();
        }
        
        return true;
    }

    protected function process() {
        
        if ($this->args) {
            globals::$my_bot->task_subject = $this->args;
            globals::$my_bot->write();
            $subject = globals::$my_bot->task_subject;
            BotAnswer::send('sendMessage', [
                'chat_id' => Bot::$chat->id,
                'text' => "Установлена тема новой задачи по умолчанию:\n\n<b>$subject</b>\n\nБот не будет запрашивать тему задачи у пользователя",
                'parse_mode' => 'HTML'
            ]);
        } else {
            globals::$my_bot->task_subject = null;
            globals::$my_bot->write();
            BotAnswer::send('sendMessage', [
                'chat_id' => Bot::$chat->id,
                'text' => <<<FIN
                    Тема новой задачи по умолчанию сброшена. Первое сообщение пользователя будет считаться темой задачи. При необходимости измените приветствие соответствующим образом.
                
                    Для установки темы по умолчанию используйте:
                    /setsubject <b>Тема</b>
                    FIN,
                'parse_mode' => 'HTML'
            ]);
        }
        
    }
}
