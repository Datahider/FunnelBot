<?php

namespace losthost\FunnelBot\data;

use losthost\DB\DBObject;

class message_data extends DBObject {
    
    const METADATA = [
        'id' => 'BIGINT(20) NOT NULL AUTO_INCREMENT',
        'task_id' => 'BIGINT(20) NOT NULL',
        'message' => 'TEXT(8192) NOT NULL',
        'PRIMARY KEY' => 'id',
        'INDEX TASK' => 'task_id'
    ];
    
    public function __set($name, $value) {
        if ($name == 'message') {
            $value = serialize($value);
        }
        parent::__set($name, $value);
    }
    
    public function __get($name) {
        $value = parent::__get($name);
        if ($name == 'message') {
            $value = unserialize($value);
        }
        return $value;
    }
}
