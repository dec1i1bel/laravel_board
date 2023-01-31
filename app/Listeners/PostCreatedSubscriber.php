<?php

namespace App\Listeners;

use App\Events\PostCreatedDiscord;
use App\Events\PostCreatedMail;

class PostCreatedSubscriber
{
    private $post;
    private $token;
    private $url;
    private $chatId;
    private $message;

    public function subscribe($events)
    {
        $events->listen(
            PostCreatedDiscord::class,
            [PostCreatedSubscriber::class, 'handleDiscord']
        );

        $events->listen(
            PostCreatedMail::class,
            [PostCreatedSubscriber::class, 'handleMail']
        );
    }
    
    /**
     * @param  App\Events\PostCreatedDiscord  $event
     * @return void
     */
    public function handleDiscord(PostCreatedDiscord $event)
    {
        $this->post = $event->post;
        $webhookurl = 'https://discord.com/api/webhooks/991737670667603979/-aZDgl_jlkRFxPRIIP9XHYWUoH-0vvrhHWh93CsA0Lx8tis8NfXKsUE8DtuJ_Mc29J7e';

        $timestamp = date("c", strtotime("now"));

        $json_data = json_encode([
            'content' => 'LBoard - добавлено объявление',
            "username" => "LBoard",
            "tts" => false,
            "embeds" => [
                [
                    "title" => $this->post->title,
                    "type" => "rich",
                    "description" => $this->post->content,
                    "url" => "https://github.com/dec1i1bel?tab=repositories",
                    "timestamp" => $timestamp,
                    "color" => hexdec( "3366ff" ),
                    "footer" => [
                        "text" => "GitHub.com/dec1i1bel",
                        "icon_url" => "https://avatars.githubusercontent.com/u/36947851?v=4"
                    ],
                    "image" => [
                        "url" => "http://procomputer.su/images/ustrojstvo-komp/osnovy-pc/ustroystvo-pc/sostav-pc.jpg"
                    ],
                    "author" => [
                        "name" => "Mr. Someuser",
                        "url" => "https://github.com/dec1i1bel?tab=repositories"
                    ],
                    "fields" => [
                        [
                            "name" => "цена",
                            "value" => $this->post->price.' руб.',
                            "inline" => false
                        ]
                    ]
                ]
            ]
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );

        $ch = curl_init( $webhookurl );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
        curl_setopt( $ch, CURLOPT_POST, 1);
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $json_data);
        curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt( $ch, CURLOPT_HEADER, 0);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec( $ch );

        curl_close( $ch );
    }
    /**
     * @param  App\Events\PostCreatedMail  $event
     * @return void
     */
    public function handleMail(PostCreatedMail $event)
    {
        mail(
            'v.balabanov85@gmail.com',
            'test from handleMail',
            'message from handlemail',
        );
    }
}
