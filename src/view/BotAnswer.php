<?php

namespace losthost\FunnelBot\view;

use losthost\telle\Bot;

class BotAnswer {
    
    static public function send(string $method, array $params) {
        header("Content-Type: application/json");
        $params['method'] = $method;
        
        Bot::logComment(print_r($params, true));
        echo json_encode($params);
    }
}
