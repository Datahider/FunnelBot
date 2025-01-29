<?php

use losthost\telle\Bot;
use losthost\FunnelBot\data\bot_data;
use losthost\FunnelBot\data\message_data;
use losthost\FunnelBot\data\task_data;

Bot::logComment('bot_data init');
bot_data::initDataStructure();
Bot::logComment('task_data init');
task_data::initDataStructure();
Bot::logComment('message_data init');
message_data::initDataStructure();

Bot::logComment('handlers init');
Bot::addHandler(\losthost\FunnelBot\controller\callback\CallbackDone::class);

Bot::addHandler(\losthost\FunnelBot\controller\command\CheckSetup::class);
Bot::addHandler(losthost\FunnelBot\controller\command\CommandStart::class);
Bot::addHandler(\losthost\FunnelBot\controller\command\CommandSetHello::class);
Bot::addHandler(losthost\FunnelBot\controller\command\CommandSetSubject::class);

Bot::addHandler(losthost\FunnelBot\controller\misc\CheckSetup::class);
Bot::addHandler(losthost\FunnelBot\controller\misc\StageProcessor::class);
