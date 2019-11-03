<?php

declare(strict_types=1);

namespace Helicon\ObjectTypeParser;

use Zend\Code\Reflection\FileReflection;

class UseTypeFinder
{
    private $reflectionClass;

    /**
     * @var array
     */
    private $uses;

    private const SUPPORTED_TYPES = [
        'boolean',
        'bool',
        'integer',
        'int',
        'string',
        'float',
        'double',
    ];

    /**	
     * @param \ReflectionClass $reflectionClass	
     */
    public function __construct(\ReflectionClass $reflectionClass)
    {
        $this->reflectionClass = $reflectionClass;
        $this->uses = (new FileReflection($reflectionClass->getFileName()))->getUses();
    }

    public function __invoke(string $type)
    {
        if (is_scalar_type_name($type)) {
            return $type;
        }

        if (class_exists($type)) {
            return $type;
        }

        return $this->find($type);
    }

    private function find(string $className): string
    {
        foreach ($this->uses as $target) {
            if ($target['as'] === $className) {
                return $target['use'];
            }
            $namespaces = explode('\\', $target['use']);
            $checkClass = array_pop($namespaces);
            if ($checkClass === $className) {
                return $target['use'];
            }
        }

        if ($this->reflectionClass->getShortName() === $className) {
            return $this->reflectionClass->getName();
        }

        $targetClassName = "{$this->reflectionClass->getNamespaceName()}\\{$className}";
        if (class_exists($targetClassName)) {
            return $targetClassName;
        }

        throw new \RuntimeException('class not found '.$className);
    }
}
