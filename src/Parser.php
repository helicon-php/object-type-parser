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
    public function __invoke(string $className): array
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
        $isPHP74Then = version_compare(PHP_VERSION, '7.4', '>=');
        $schema = [];
        foreach ($refClass->getProperties() as $property) {
            if ($isPHP74Then) {
                $result = $this->parse($property, $finder);
            } else {
                $result = $this->parseLessPHP73($property, $finder);
            }
            $schema[$result[0]] = ['type' => $result[1]];
        }

        return $schema;
    }

    private function parse(\ReflectionProperty $property, UseTypeFinder $finder): array
    {
        $type = $property->getType();
        if (null === $type) {
            return $this->parseLessPHP73($property, $finder);
        }

        $name = $type->getName();
        if ('self' === $name) {
            return [$property->getName(), $property->getDeclaringClass()->getName()];
        }

        return [$property->getName(), $name];
    }

    private function parseLessPHP73(\ReflectionProperty $property, UseTypeFinder $finder): array
    {
        $comment = $property->getDocComment();
        $commentReflection = new DocBlockReflection($comment);
        $generator = DocBlockGenerator::fromReflection($commentReflection);
        foreach ($generator->getTags() as $tag) {
            if ($tag instanceof VarTag) {
                if ('self' === $tag->getTypes()[0]) {
                    return [$property->getName(), $property->getDeclaringClass()->getName()];
                } else {
                    return [$property->getName(), ($finder)($tag->getTypes()[0])];
                }
            }
        }
    }

//    public function parse(\ReflectionClass  $reflectionClass) :array
//    {
//        $schema = [];
//
//        foreach ($reflectionClass->getProperties() as $property) {
//            $type = $property->getType();
//            $name = $type->getName();
//            if ($name === 'self') {
//                $schema[$property->getName()] = [
//                    'type' => $reflectionClass->getName(),
//                ];
//            } else {
//                $schema[$property->getName()] = [
//                    'type' => $name
//                ];
//            }
//        }
//        return $schema;
//    }
//
//
//    public function parseOld(\ReflectionClass  $reflectionClass) :array
//    {
//        $finder = new UseTypeFinder($reflectionClass);
//
//        foreach ($reflectionClass->getProperties() as $property) {
//            $comment = $property->getDocComment();
//            $commentReflection = new DocBlockReflection($comment);
//            $generator = DocBlockGenerator::fromReflection($commentReflection);
//
//            foreach ($generator->getTags() as $tag) {
//                if ($tag instanceof VarTag) {
//                    if ('self' === $tag->getTypes()[0]) {
//                        $schema[$property->getName()] = [
//                            'type' => $reflectionClass->getName(),
//                        ];
//                    } else {
//                        $schema[$property->getName()] = [
//                            'type' => ($finder)($tag->getTypes()[0]),
//                        ];
//                    }
//                }
//            }
//        }
//
//        return $schema;
//    }
}
