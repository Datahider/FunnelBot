<?php

namespace losthost\FunnelBot\data;

use losthost\DB\DBObject;

class task_data extends DBObject {
    
    const METADATA = [
        'id' => 'BIGINT(20) NOT NULL AUTO_INCREMENT',
        'bot_id' => 'BIGINT(20) NOT NULL', 
        'user_id' => 'BIGINT(20) NOT NULL',
        'stage' => 'ENUM("sethello", "subject", "description", "identity", "done")',
        'subject' => 'VARCHAR(1024)',
        'messages' => 'VARCHAR(1024)',
        'message_to_delete' => 'BIGINT(20)',
        'company_name' => 'VARCHAR(128)',
        'company_logo' => 'VARCHAR(128)',
        'group_link' => 'VARCHAR(128)',
        'PRIMARY KEY' => 'id',
        'UNIQUE INDEX BOT_USER_IDS' => ['bot_id', 'user_id']
    ];
    
    public function __set($name, $value) {
        if ($name == 'messages' && !is_null($value)) {
            $value = serialize($value);
        }
        parent::__set($name, $value);
    }
    
    public function __get($name) {
        $value = parent::__get($name);
        if ($name == 'messages' && !is_null($value)) {
            $value = unserialize($value);
        }
        return $value;
    }
}
