<?php

declare(strict_types=1);

namespace Helicon\ObjectTypeParser;

if (false === \function_exists('is_scalar_type_name')) {
    function is_scalar_type_name(string $typeName): bool
    {
        return \in_array($typeName, [
            'boolean',
            'bool',
            'integer',
            'int',
            'string',
            'float',
            'double',
        ], true);
    }
}
