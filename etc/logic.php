<?php

use losthost\telle\Bot;

Bot::addHandler(\losthost\FunnelBot\controller\callback\CallbackDone::class);

Bot::addHandler(losthost\FunnelBot\controller\command\CommandStart::class);
Bot::addHandler(\losthost\FunnelBot\controller\command\CommandSetHello::class);
Bot::addHandler(losthost\FunnelBot\controller\command\CommandSetSubject::class);
