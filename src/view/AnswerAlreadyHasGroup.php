<?php

namespace losthost\FunnelBot\view;

use losthost\FunnelBot\view\BotAnswer;
use losthost\telle\Bot;
use losthost\DB\DBValue;
use losthost\FunnelBot\misc\globals;

class AnswerAlreadyHasGroup {
    
    static public function do() {

        $group = new DBValue('SELECT * FROM sprt_funnel_chat WHERE owner_id = ? AND customer_id = ?', [globals::$my_bot->admin_id, Bot::$user->id]);
        
        BotAnswer::send('sendMessage', [
            'chat_id' => Bot::$chat->id,
            'text' => "Создать новую задачу или просмотреть/дополнить информацию по старым вы можете в группе: $group->invite_link"
        ]);
    }
}
