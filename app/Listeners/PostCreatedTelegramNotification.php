<?php

namespace App\Listeners;

use App\Events\PostCreatedTelegram;

class PostCreatedTelegramNotification
{
    private $post;
    private $token;
    private $url;
    private $chatId;
    private $message;
    /**
     * @param  App\Events\PostCreatedTelegram  $event
     * @return void
     */
    public function handle(PostCreatedTelegram $event)
    {
        $this->post = $event->post;
        $this->token = env('TELEGRAM_BOT_TOKEN');
        $this->chatId = '1000606796';
        $this->message = urlencode('Добавлен товар:
            - id: '.$this->post->id.'
            - наименование: '.$this->post->title.'
            - описание: '.$this->post->content.'
            - цена: '.$this->post->price.' руб.
        ');
        $this->url = 'https://api.telegram.org/bot'.$this->token.'/sendMessage?chat_id='.$this->chatId.'&text='.$this->message;

        $this->sendNotificationToBotChannel();
    }

    private function sendNotificationToBotChannel()
    {
        file_get_contents($this->url);
    }
}
