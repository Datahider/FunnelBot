<?php

namespace losthost\FunnelBot\data;

use losthost\DB\DBObject;

class bot_data extends DBObject {
    
    const METADATA = [
        'id' => 'VARCHAR(60) NOT NULL',
        'tg_id' => 'BIGINT(20) NOT NULL',
        'username' => 'VARCHAR(100) NOT NULL',
        'token' => 'VARCHAR(50) NOT NULL',
        'admin_id' => 'BIGINT(20) NOT NULL',
        'task_subject' => 'VARCHAR(300)',
        'hello_data' => 'TEXT(8192) NOT NULL',
        'PRIMARY KEY' => 'id',
        'UNIQUE INDEX TGID' => 'tg_id',
        'UNIQUE INDEX TOKEN' => 'token'
    ];
    
}
