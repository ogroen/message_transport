<?php

namespace Ogroen\MessageTransport {

    use ReflectionClass;
    use ReflectionParameter;

    class Binder
    {

        public function bind($class, array $values)
        {
            return $this->getInstance(new ReflectionClass($class), $values);
        }

        protected function getInstance(ReflectionClass $reflect, array $values)
        {
            $constructor = $reflect->getConstructor();

            if ($constructor === null) {
                return $reflect->newInstance();
            }

            $args = array_map(function (ReflectionParameter $parameter) use ($values) {
                if (false === array_key_exists($parameter->getName(), $values)) {

                    if ($parameter->isOptional()) {
                        return $parameter->getDefaultValue();
                    }

                    return null;
                }
                // TODO : Convert types types
                return $values[$parameter->getName()];
            }, $constructor->getParameters());

            return $reflect->newInstanceArgs($args);
        }

    }

}