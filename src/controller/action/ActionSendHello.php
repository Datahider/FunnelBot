<?php

namespace losthost\FunnelBot\controller\action;

use losthost\telle\Bot;
use TelegramBot\Api\BotApi;

class ActionSendHello {
    
    static public function do() {
        
        global $my_bot;
        
        $hello_value = unserialize($my_bot->hello_data);
        
        Bot::logComment('Initializing Bot API...');
        $api = new BotApi($my_bot->token);
        Bot::logComment('Bot API initialized');
        $api->call('sendMessage', [
                    'chat_id' => Bot::$chat->id,
                    'text' => $hello_value['text'],
                    'entities' => $hello_value['entities']
                ]);    
    }
}
