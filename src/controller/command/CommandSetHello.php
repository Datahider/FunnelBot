<?php

namespace losthost\FunnelBot\controller\command;

use losthost\telle\Bot;
use losthost\telle\abst\AbstractHandlerCommand;

class CommandSetHello extends AbstractHandlerCommand {
    
    const COMMAND = 'sethello';
    
    protected function handle(\TelegramBot\Api\Types\Message &$message): bool {
     
        $my_admin = Bot::getMe()->getUsername(). '_admin';
        
        if (Bot::param($my_admin, null) == Bot::$user->id) {
            $this->process($message);
        } else {
            //
        }
        
        return true;
    }
    
    protected function process() {
        
        Bot::$api->sendMessage(Bot::$chat->id, "Отправьте сообщение с новым приветствием.\n\nВы можете использовать только текстовое сообщение с любым форматированием и эмоджи.", 'html');
        \losthost\FunnelBot\controller\priority\PioritySetHello::setPriority(null);
        
    }
}
