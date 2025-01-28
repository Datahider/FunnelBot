<?php

use losthost\telle\Bot;
use losthost\FunnelBot\data\bot_data;
use losthost\FunnelBot\data\message_data;
use losthost\FunnelBot\data\task_data;

bot_data::initDataStructure();
task_data::initDataStructure();
message_data::initDataStructure();

Bot::addHandler(\losthost\FunnelBot\controller\callback\CallbackDone::class);

Bot::addHandler(\losthost\FunnelBot\controller\command\CheckSetup::class);
Bot::addHandler(losthost\FunnelBot\controller\command\CommandStart::class);
Bot::addHandler(\losthost\FunnelBot\controller\command\CommandSetHello::class);
Bot::addHandler(losthost\FunnelBot\controller\command\CommandSetSubject::class);

Bot::addHandler(losthost\FunnelBot\controller\misc\CheckSetup::class);
Bot::addHandler(losthost\FunnelBot\controller\misc\StageProcessor::class);
