<?php

declare(strict_types=1);

namespace Helicon\ObjectTypeParser;

interface ParserInterface
{
    /**
     * @return mixed
     *
     * @throws ParserException
     */
    public function __invoke(string $className);
}
