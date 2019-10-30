<?php

declare(strict_types=1);

namespace Helicon\ObjectTypeParser;

interface ParserInterface
{
    /**
     * @param string $className
     *
     * @return mixed
     *
     * @throws ParserException
     */
    public function __invoke(string $className);
}
