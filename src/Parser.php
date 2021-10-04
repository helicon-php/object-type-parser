<?php

declare(strict_types=1);

namespace Helicon\ObjectTypeParser;

class Parser implements ParserInterface
{
    /**
     * {@inheritdoc}
     */
    public function __invoke(string $className): array
    {
        if (false === class_exists($className)) {
            throw new ParserException('Class not found, '.$className);
        }

        $refClass = new \ReflectionClass($className);

        $schema = [];
        foreach ($refClass->getProperties() as $property) {
            $result = $this->parse($property);
            $schema[$result[0]] = ['type' => $result[1]];
        }

        return $schema;
    }

    private function parse(\ReflectionProperty $property): array
    {
        try {
            $type = $property->getType();
            $name = $type->getName();
            if ('self' === $name) {
                return [$property->getName(), $property->getDeclaringClass()->getName()];
            }

            return [$property->getName(), $name];
        } catch (\Throwable $e) {
            throw new ParserException('Parse error', $e->getCode(), $e);
        }
    }
}
