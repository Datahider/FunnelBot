<?php

use losthost\telle\Bot;

require 'vendor/autoload.php';

error_log('setup');
Bot::setup();

Bot::logComment('run..');
Bot::run();