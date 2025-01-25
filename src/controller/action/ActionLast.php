<?php

namespace losthost\FunnelBot\controller\action;

use losthost\telle\Bot;

class ActionLast {
    
    static public function do() {
        
        $obj = new static();
        $obj->prepareGroup();
        $obj->sendLink();
    }
    
    public function prepareGroup() {
        
    }
    
    public function sendLink() {
        Bot::$api->sendMessage(Bot::$chat->id, 'Тут должна быть благодарность, короткая инструкция и ссылка на группу.', 'HTML');
    }
}
