<?php

declare(strict_types=1);

namespace Helicon\ObjectTypeParser;

use Laminas\Code\Generator\DocBlock\Tag\VarTag;
use Laminas\Code\Generator\DocBlockGenerator;
use Laminas\Code\Reflection\DocBlockReflection;

class Parser implements ParserInterface
{
    /**
     * {@inheritdoc}
     */
    public function __invoke(string $className)
    {
        if (false === class_exists($className)) {
            throw new ParserException('Class not found, '.$className);
        }

        try {
            $refClass = new \ReflectionClass($className);
        } catch (\ReflectionException $e) {
            throw new ParserException('Reflection error', $e->getCode(), $e);
        }

        $finder = new UseTypeFinder($refClass);

        $schema = [];
        foreach ($refClass->getProperties() as $property) {
            $comment = $property->getDocComment();
            $commentReflection = new DocBlockReflection($comment);
            $generator = DocBlockGenerator::fromReflection($commentReflection);

            foreach ($generator->getTags() as $tag) {
                if ($tag instanceof VarTag) {
                    if ('self' === $tag->getTypes()[0]) {
                        $schema[$property->getName()] = [
                            'type' => $refClass->getName(),
                        ];
                    } else {
                        $schema[$property->getName()] = [
                            'type' => ($finder)($tag->getTypes()[0]),
                        ];
                    }
                }
            }
        }

        return $schema;
    }
}
