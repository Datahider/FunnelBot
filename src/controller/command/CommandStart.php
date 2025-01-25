<?php

namespace losthost\FunnelBot\controller\command;

use losthost\telle\abst\AbstractHandlerCommand;
use losthost\telle\Bot;
use losthost\telle\model\DBBotParam;
use losthost\FunnelBot\controller\action\ActionSendHello;
use losthost\FunnelBot\controller\priority\PrioritySubject;
use losthost\FunnelBot\controller\priority\PriorityDescription;


class CommandStart extends AbstractHandlerCommand {
    
    const COMMAND = 'start';
    
    protected \TelegramBot\Api\Types\Message $message;
    protected string $my_username;
    protected string $your_name;

    protected function handle(\TelegramBot\Api\Types\Message &$message): bool {
        
        $this->message = $message;
        $this->my_username = Bot::getMe()->getUsername();
        $this->your_name = trim("{$message->getFrom()->getFirstName()} {$message->getFrom()->getLastName()}");

        $admin_param_name = "{$this->my_username}_admin";
        $admin_param = new DBBotParam($admin_param_name, false);

        $subject_param_name = "{$this->my_username}_subject";
        $subject_param = new DBBotParam($subject_param_name, null);
        
        if (!$admin_param->value) {
            $admin_param->value = Bot::$user->id;
        }
        
        if ($admin_param->value == Bot::$user->id) {
            $this->helloAdmin();
        } elseif ($subject_param->value) {
            $this->sendHello();
            $data['subject'] = $subject_param->value;
            PriorityDescription::setPriority($data);
        } else {
            $this->sendHello();
            PrioritySubject::setPriority(null);
        }

        return true;
    }
    
    protected function helloAdmin() {
    
        $subject_param_name = Bot::getMe()->getUsername(). '_subject';
        $subject_param = new DBBotParam($subject_param_name, null);
        
        if ($subject_param->value) {
            $subject_info = "Тема задачи по умолчанию: <b>$subject_param->value</b>";
        } else {
            $subject_info = "Тема задачи по умолчанию не установлена.";
        }
        
        $text1 = "Привет! Вы первый пользователь, с которым я общаюсь, поэтому теперь вы мой хозяин.\n\nДругих пользователей я буду приветствовать от вашего имени следующим сообщением:";
        $text2 = <<<FIN
                Если вы хотите изменить приветствие, нажмите /sethello
                
                $subject_info
                
                Используйте /setsubject для установки или сброса темы задачи по умолчанию
                FIN;
        Bot::$api->sendMessage(Bot::$chat->id, $text1, 'html');
        sleep(2);
        $this->sendHello();
        sleep(2);
        Bot::$api->sendMessage(Bot::$chat->id, $text2, 'html');
    }
    
    protected function sendHello() {
        
        $default_hello_value = [
            'text' => "Привет! Меня зовут $this->your_name. Я умею всё и даже чуть-чуть больше! Что привело вас сюда?",
            'entities' => []
        ];
        new DBBotParam("{$this->my_username}_hello", serialize($default_hello_value));
        
        ActionSendHello::do();
    }
}
