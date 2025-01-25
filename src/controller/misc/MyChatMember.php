<?php

namespace losthost\FunnelBot\controller\misc;

use losthost\telle\abst\AbstractHandlerMyChatMember;
use losthost\telle\Bot;
use losthost\FunnelBot\data\group_pool;
use losthost\telle\model\DBBotParam;

class MyChatMember extends AbstractHandlerMyChatMember {

    const OBERBOT_ID = 674983837;
    
    protected $chat_id;
    protected $chat_type;
    protected $new_status;
    
    protected function check(\TelegramBot\Api\Types\ChatMemberUpdated &$chat_member): bool {

        $chat = $chat_member->getChat();
        $this->chat_id = $chat->getId();
        $this->chat_type = $chat->getType();
        $this->new_status = $chat_member->getNewChatMember()->getStatus();

        if ($this->chat_type == 'supergroup') {
            return true;
        }
        
        return false;
    }

    protected function handle(\TelegramBot\Api\Types\ChatMemberUpdated &$chat_member): bool {
        
        $group_in_pool = new group_pool(['id' => $this->chat_id], true);
        
        if ($this->new_status == 'administrator' && $group_in_pool->isNew()) {

            if ($this->isGroupSetUp()) {
                $group_in_pool->owner = $this->getBotOwnerId();
                $group_in_pool->is_used = false;
                $group_in_pool->write();
                Bot::logComment("$this->chat_id is added to the pool");

                $message = Bot::$api->sendMessage($group_in_pool->id, 'Группа добавлена в пул для дальнейшего использования...');
                sleep(3);
                Bot::$api->deleteMessage($group_in_pool->id, $message->getMessageId());
            } else {
                Bot::$api->leaveChat($group_in_pool->id);
                Bot::logComment("$this->chat_id is left by bot");
            }
        } else {
            Bot::logComment("Ignored as status is $this->new_status");
        }
        
        return true;
    }
    
    protected function isGroupSetUp() {
        
        $ok = true;
        
        if (!$this->isOberBotAdmin()) {
            $ok = false;
            $this->sendError('@Oberbot должен быть администратором');
        }
        
        if (!$this->isOwnerAdmin()) {
            $ok = false;
            $this->sendError('Мой хозяин должен быть администратором');
        }
        
        if (!$this->isForum()) {
            $ok = false;
            $this->sendError('В группе должны быть включены темы');
        }
        
        // TODO -- перенести эту проверку в Action-класс
        
        return $ok;
    }
    
    protected function getChatOwnerId() {
        
        foreach (Bot::$api->getChatAdministrators(Bot::$chat->id) as $chat_admin) {
            if ($chat_admin->getStatus() == 'creator') {
                return $chat_admin->getUser()->getId();
            }
        }
        
        return null;
    }
    
    protected function getBotOwnerId() {

        $admin_param_name = Bot::getMe()->getUsername(). "_admin";
        $admin_param = new DBBotParam($admin_param_name, false);
        return $admin_param->value;
    }
}
