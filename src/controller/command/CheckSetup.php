<?php

namespace losthost\FunnelBot\controller\command;

use losthost\telle\abst\AbstractHandlerCommand;
use losthost\FunnelBot\data\bot_data;
use losthost\FunnelBot\view\BotAnswer;
use losthost\telle\Bot;

class CheckSetup extends AbstractHandlerCommand {

    const COMMAND = '*';
    const MY_HEADER = 'HTTP_X_TELEGRAM_BOT_API_SECRET_TOKEN';
    
    protected function check(\TelegramBot\Api\Types\Message &$message): bool {
        
        global $my_bot;
        
        $id = filter_input(INPUT_SERVER, self::MY_HEADER);
        
        $my_bot = new bot_data(['id' => $id], true);
        if ($my_bot->isNew()) {
            return true;
        }
        
        Bot::logComment('Bot is found: '. $my_bot->username);
        return false;
    }
    
    protected function handle(\TelegramBot\Api\Types\Message &$message): bool {
        
        BotAnswer::send('sendMessage', [
            'chat_id' => $message->getChat()->getId(),
            'text' => <<<FIN
                Привет! 
                    
                Для работы сначала необходимо настроить бота с помощью @FunnelSetupBot
                FIN
        ]);
        return true;
    }
}
