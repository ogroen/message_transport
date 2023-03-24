<?php

namespace Ogroen\MessageTransport\transport;

use GuzzleHttp\Client;
use Ogroen\Messages\Message;
use Ogroen\MessageTransport\MessageTransportInterface;

class HttpTransport implements MessageTransportInterface
{
    private $url;
    private $login;
    private $password;
    /**
     * @var Client
     */
    private $httpClient;

    public function __construct(Client $httpClient, $params)
    {
        $this->url = $params['url'];
        $this->login = $params['login'];
        $this->password = $params['password'];
        $this->httpClient = $httpClient;
    }

    public function send(Message $message) : void
    {
        $json = $message->serialize();

        $postData = [
            'message' => [
                'name' => get_class($message),
                'body' => $json,
            ]
        ];

        $this->httpClient->post($this->url, ['form_params' => $postData]);
    }

    public function receive(string $request): Message
    {
        $message = Message::deserialize($request);

        $this->log(sprintf("Receive message - '{$request}'"));

        return $message;
    }

    protected function log($message)
    {
        echo $message."\r\n";
    }
}