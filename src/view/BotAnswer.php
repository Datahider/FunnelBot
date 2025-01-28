<?php

namespace losthost\FunnelBot\view;

class BotAnswer {
    
    static public function send(string $method, array $params) {
        header("Content-Type: application/json");
        $params['method'] = $method;
        
        echo json_encode($params);
    }
}
