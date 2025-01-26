<?php

namespace losthost\FunnelBot\controller\action;

use losthost\telle\Bot;
use TelegramBot\Api\BotApi;

class ActionLast {
    
    protected string $link;
    
    static public function do() {
        
        $obj = new static();
        $obj->prepareGroup();
        $obj->sendLink();
    }
    
    public function prepareGroup() {
        
    }
    
    public function sendLink() {
        
        global $my_bot;
        
        $api = new BotApi($my_bot->token);
        $api->sendMessage(Bot::$chat->id, 'Тут должна быть благодарность, короткая инструкция и ссылка на группу.', 'HTML');
    }
}
