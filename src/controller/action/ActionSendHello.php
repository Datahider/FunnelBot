<?php

namespace losthost\FunnelBot\controller\action;

use losthost\telle\Bot;
use TelegramBot\Api\BotApi;
use losthost\FunnelBot\misc\globals;

class ActionSendHello {
    
    static public function do() {
        
        $hello_value = unserialize(globals::$my_bot->hello_data);
        
        $api = new BotApi(globals::$my_bot->token);
        $api->call('sendMessage', [
                    'chat_id' => Bot::$chat->id,
                    'text' => $hello_value['text'],
                    'entities' => $hello_value['entities']
                ]);  
    }
}
