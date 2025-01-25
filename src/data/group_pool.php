<?php

namespace losthost\FunnelBot\data;

use losthost\DB\DBObject;

class group_pool extends DBObject {
    
    const METADATA = [
        'id' => 'BIGINT(20) NOT NULL',
        'owner' => 'BIGINT(20) NOT NULL',
        'is_used' => 'TINYINT(1) NOT NULL',
        'PRIMARY KEY' => 'id',
        'INDEX OWNER_USED' => ['owner', 'is_used']
    ];
    
}
