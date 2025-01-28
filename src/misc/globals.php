<?php

namespace losthost\FunnelBot\misc;

use losthost\FunnelBot\data\bot_data;
use losthost\telle\Bot;
use losthost\DB\DBValue;

class globals {
    
    static public bot_data $my_bot;
    static public string $ober_token;

    static public function isAdmin() {
        return globals::$my_bot->admin_id == Bot::$user->id;
    }
    
    static public function hasGroup() {
        $group_count = new DBValue('SELECT COUNT(*) AS value FROM `sprt_funnel_chat` WHERE owner_id = ? AND customer_id = ?', 
                [globals::$my_bot->admin_id, Bot::$user->id]);
        return (bool)$group_count->value;
    }
}
