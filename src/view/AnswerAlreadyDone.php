<?php

namespace losthost\FunnelBot\view;

use losthost\FunnelBot\view\BotAnswer;
use losthost\telle\Bot;

class AnswerAlreadyDone {
    
    static public function do(string $group_link) {

        BotAnswer::send('sendMessage', [
            'chat_id' => Bot::$chat->id,
            'text' => <<<FIN
                Этот бот уже выполнил своё предназначение.

                Подробная информация по вашим задачам в группе: $group_link
                FIN
        ]);
    }
}
