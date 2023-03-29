<?php

namespace Ogroen\MessageTransport;

use Ogroen\Messages\MessageSerializer;

interface MessageSenderInterface
{
    public function send(MessageSerializer $message) : void;
}