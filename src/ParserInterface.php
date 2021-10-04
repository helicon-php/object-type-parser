<?php

declare(strict_types=1);

namespace Helicon\ObjectTypeParser;

interface ParserInterface
{
    /**
     * @throws ParserException
     */
    public function __invoke(string $className): mixed;
}
