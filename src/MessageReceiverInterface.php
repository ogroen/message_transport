<?php

namespace Ogroen\MessageTransport;

interface MessageReceiverInterface
{
    public function receive(string $request);
}