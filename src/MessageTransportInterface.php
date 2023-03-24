<?php

namespace Ogroen\MessageTransport;

use Ogroen\Messages\Message;

interface MessageTransportInterface
{
    public function send(Message $message) : void;

    public function receive(string $request) : Message;
}