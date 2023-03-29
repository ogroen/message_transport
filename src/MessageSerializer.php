<?php

namespace Ogroen\MessageTransport;

use ReflectionClass;
use ReflectionProperty;

class MessageSerializer
{
    /**
     * @var \Ogroen\MessageTransport\Binder
     */
    private $binder;

    public function __construct(\Ogroen\MessageTransport\Binder $binder)
    {
        $this->binder = $binder;
    }

    public function serialize($message)
    {
        $serialize = [
            'class' => get_class($message),
            'fields' => []
        ];

        $reflection = new ReflectionClass($message);

        foreach ($reflection->getProperties(ReflectionProperty::IS_PRIVATE) as $v) {
            $serialize['fields'][$v->getName()] = $message->{'get'.ucfirst($v->getName())}();
        }

        return json_encode($serialize);
    }

    public function deserialize(string $string)
    {
        $array = json_decode($string, true);

        if (!is_array($array) || !array_key_exists('class', $array)) {
            throw new \Exception('Don\'t have class field');
        }

        if (!class_exists($array['class'])) {
            throw new \Exception('Class "'.$array['class'].'" don\'t exist');
        }

        $obj = $this->binder->bind($array['class'], array_key_exists('fields', $array) ? $array['fields'] : []);

        return $obj;
    }
}