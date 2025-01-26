<?php

namespace losthost\FunnelBot\controller\command;

use losthost\telle\Bot;
use losthost\telle\abst\AbstractHandlerCommand;
use losthost\FunnelBot\view\BotAnswer;
use TelegramBot\Api\BotApi;

class CommandSetHello extends AbstractHandlerCommand {
    
    const COMMAND = 'sethello';
    
    protected function handle(\TelegramBot\Api\Types\Message &$message): bool {
     
        global $my_bot;
        
        if ($my_bot->admin_id == Bot::$user->id) {
            $this->process($message);
        } else {
            Bot::logComment('You are not admin. Admin is: '. $my_bot->admin_id);
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
