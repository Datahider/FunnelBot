<?php

namespace losthost\FunnelBot\controller\action;

use losthost\telle\Bot;
use losthost\telle\model\DBBotParam;

class ActionSendHello {
    
    static public function do() {
        
        $hello_param_name = Bot::getMe()->getUsername(). '_hello';
        $hello_param = new DBBotParam($hello_param_name, null);
        
        $hello_value = unserialize($hello_param->value);
        
        Bot::$api->call('sendMessage', [
                    'chat_id' => Bot::$chat->id,
                    'text' => $hello_value['text'],
                    'entities' => $hello_value['entities']
                ]);    
    }
}
