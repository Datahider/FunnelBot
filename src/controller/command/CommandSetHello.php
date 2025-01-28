<?php

namespace losthost\FunnelBot\controller\command;

use losthost\telle\Bot;
use losthost\telle\abst\AbstractHandlerCommand;
use losthost\FunnelBot\view\BotAnswer;
use TelegramBot\Api\BotApi;
use losthost\FunnelBot\misc\globals;

class CommandSetHello extends AbstractHandlerCommand {
    
    const COMMAND = 'sethello';
    
    protected function handle(\TelegramBot\Api\Types\Message &$message): bool {
     
        if (globals::isAdmin()) {
            $this->process($message);
        }
        
        return true;
    }
    
    protected function process() {
        
        $message_data = [
            'text' => "Отправьте сообщение с новым приветствием.\n\nВы можете использовать только текстовое сообщение с любым форматированием и эмоджи.",
            'parse_mode' => 'HTML',
            'chat_id' => Bot::$chat->id
        ];
        
        BotAnswer::send('sendMessage', $message_data);
        \losthost\FunnelBot\controller\priority\PioritySetHello::setPriority(null);
        
    }
}
