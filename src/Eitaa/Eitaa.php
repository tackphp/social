<?php

namespace Social\Eitaa;

use Social\Response;

class Eitaa
{
    protected $url = "eitaayar.ir/api";

    protected $client;

    protected $token;

    public function __construct(\GuzzleHttp\Client $client, $token)
    {
        $this->client = $client;
        $this->token = $token;
    }

    public function url($url)
    {
        $this->url = $url;

        return $this;
    }

    public function client($client)
    {
        $this->client = $client;

        return $this;
    }

    public function token($token)
    {
        $this->token = $token;

        return $this;
    }

    protected function getUrl(?string $methodName = null): string
    {
        return 'https://'.$this->url.'/'.$this->token.'/'.$methodName;
    }

    public function sendMessage(
        int $chatId,
        string $text,
        ?string $title = null,
        int $disableNotification = 0,
        ?int $replyToMessageId = null,
        ?string $date = null,
        int $pin = 0,
        ?int $viewCountForDelete = null,
    ): Response
    {
        $response = $this->client->post($this->getUrl('sendMessage'), [
            'json' => [
                'chat_id' => $chatId,
                'text' => $text,
                'title' => $title,
                'disable_notification' => $disableNotification,
                'reply_to_message_id' => $replyToMessageId,
                'date' => $date,
                'pin' => $pin,
                'view_count_for_delete' => $viewCountForDelete,
            ],
        ],[
            'content-type' => 'application/json'
        ]);

        return new Response($response);
    }

    public function sendFile(
        int $chatId,
        mixed $file,
        ?string $caption = null,
        ?string $title = null,
        int $disableNotification = 0,
        ?int $replyToMessageId = null,
        ?string $date = null,
        int $pin = 0,
        ?int $viewCountForDelete = null,
    ): Response
    {
        $response = $this->client->post($this->getUrl('sendFile'), [
            'json' => [
                'chat_id' => $chatId,
                'file' => $file,
                'caption' => $caption,
                'title' => $title,
                'disable_notification' => $disableNotification,
                'reply_to_message_id' => $replyToMessageId,
                'date' => $date,
                'pin' => $pin,
                'view_count_for_delete' => $viewCountForDelete,
            ],
        ]);

        return new Response($response);
    }

    public function getMe(): Response
    {
        $response = $this->client->get($this->getUrl('getMe'));

        return new Response($response);
    }
}
