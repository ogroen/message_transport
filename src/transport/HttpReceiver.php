<?php

namespace Ogroen\MessageTransport\transport;

use Ogroen\MessageTransport\MessageSerializer;
use Ogroen\MessageTransport\MessageReceiverInterface;

class HttpReceiver implements MessageReceiverInterface
{
    /**
     * @var MessageSerializer
     */
    private $serializer;

    public function __construct(MessageSerializer $serializer)
    {
        $this->serializer = $serializer;
    }

    public function receive(string $request)
    {
        $a = json_decode($request, true);

        $message = $this->serializer->deserialize($a['body']);

        $this->log(sprintf("Receive message - '{$request}'"));

        return $message;
    }

    protected function log($message)
    {
        echo $message."\r\n";
    }
}