<?php

namespace Ogroen\MessageTransport\transport;

use GuzzleHttp\Client;
use Ogroen\MessageTransport\MessageSerializer;
use Ogroen\MessageTransport\MessageSenderInterface;

class HttpSender implements MessageSenderInterface
{
    private $url;
    private $login;
    private $password;
    /**
     * @var Client
     */
    private $httpClient;
    /**
     * @var MessageSerializer
     */
    private $serializer;

    public function __construct(MessageSerializer $serializer, Client $httpClient, $params)
    {
        $this->url = $params['url'];
        $this->login = $params['login'];
        $this->password = $params['password'];
        $this->httpClient = $httpClient;
        $this->serializer = $serializer;
    }

    public function send($message) : void
    {
        $json = $this->serializer->serialize($message);

        $postData = [
            'message' => [
                'name' => get_class($message),
                'body' => $json,
            ]
        ];

        $this->httpClient->post($this->url, [
            'form_params' => $postData,
            'auth' => [$this->login, $this->password]
        ]);
    }

    protected function log($message)
    {
        echo $message."\r\n";
    }
}