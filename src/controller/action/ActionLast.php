<?php

namespace losthost\FunnelBot\controller\action;

use losthost\telle\Bot;
use TelegramBot\Api\BotApi;
use losthost\DB\DB;
use losthost\DB\DBValue;
use losthost\FunnelBot\data\task_data;
use losthost\FunnelBot\misc\globals;

class ActionLast {
    
    protected string $link;
    protected DBValue $group;
    protected BotApi $ober_api;
    protected BotApi $my_api;
    protected task_data $task;


    static public function do() {
        
        $obj = new static();
        $obj->prepareGroup();
        $obj->queueTask();
        $obj->sendLink();
    }
    
    public function __construct() {
        $this->ober_api = new BotApi(globals::$ober_token);
        $this->my_api = new BotApi(globals::$my_bot->token);
        $this->task = new task_data(['bot_id' => globals::$my_bot->tg_id, 'user_id' => Bot::$user->id]);
    }
    
    protected function queueTask() {
        $job_params = [
            'start_time' => date_create()->format(DB::DATE_FORMAT),
            'start_in_background' => 0,
            'job_class' => 'losthost\Oberbot\background\CreateJobFromFunnel',
            'job_args' => $this->task->id,
        ];
        $sth = DB::prepare(<<<FIN
                INSERT INTO sprt_telle_pending_jobs
                (start_time, start_in_background, job_class, job_args)
                VALUES(:start_time, :start_in_background, :job_class, :job_args)
                FIN);
        $sth->execute($job_params);
    }
    
    protected function getGroup() {
        
        $sth = DB::prepare(<<<FIN
                UPDATE 
                    sprt_funnel_chat 
                SET 
                    customer_id = ?
                WHERE 
                    owner_id = ? 
                    AND (customer_id IS NULL
                        OR customer_id = ?) 
                LIMIT 1
                FIN);
        $sth->execute([Bot::$user->id, globals::$my_bot->admin_id, Bot::$user->id]);
        
        $group = new DBValue('SELECT * FROM sprt_funnel_chat WHERE customer_id = ?', [Bot::$user->id]);
        return $group;
    }
    
    public function prepareGroup() {
        
        $this->group = $this->getGroup();
        $this->setChatTitle($this->group->id, $this->task->company_name);

        $logo = $this->getLocalLogo($this->task->company_logo);
        $this->setChatPhoto($this->group->id, $logo);
        unlink($logo);
        
        $this->link = $this->group->invite_link;
        $this->task->group_id = $this->group->id;
        $this->task->write();
    }

    protected function setChatPhoto(int $chat_id, string $path) {
        
        $photo = new \CURLFile($path);
        try {
            $this->ober_api->setChatPhoto($chat_id, $photo);
        } catch (\Exception $e) {
            Bot::logException($e);
        }
        
    }
    
    protected function setChatTitle(int $chat_id, string $title) {

        try {
            $this->ober_api->setChatTitle($chat_id, $title);
        } catch (\Exception $e) {
            Bot::logException($e);
        }
    }
    
    protected function getLocalLogo(string $file_id) {

        $token = globals::$my_bot->token;
        $file = $this->my_api->getFile($file_id);
        $path = $file->getFilePath();
        $url = "https://api.telegram.org/file/bot$token/$path";
        $data = file_get_contents($url);
        $tmpfile = tempnam("/tmp", 'fun');
        file_put_contents($tmpfile, $data);
        return $tmpfile;
    }
    
    public function sendLink() {
        
        $this->my_api->sendMessage(Bot::$chat->id, <<<FIN
                Спасибо! Всё готово. Ваша задача размещена в моей системе учета задач.
                
                Отслеживать её выполнение, предоставить дополнительную информацию по задаче или создать новую задачу вы можете в группе: $this->link
                FIN, 'HTML');
    }
}
