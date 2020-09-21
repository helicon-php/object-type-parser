<?php

declare(strict_types=1);

namespace Helicon\ObjectTypeParser\Tests;

use Helicon\ObjectTypeParser\Parser;
use Helicon\ObjectTypeParser\ParserException;
use Helicon\ObjectTypeParser\Tests\Age\Age;
use Helicon\ObjectTypeParser\Tests\Age\Name;
use PHPUnit\Framework\TestCase;

class ParserTest extends TestCase
{
    public function testParse(): void
    {
        $parser = new Parser();
        $schema = ($parser)(Friend::class);
        $this->assertSame([
            'id' => [
                'type' => 'int',
            ],
            'name' => [
                'type' => Name::class,
            ],
            'age' => [
                'type' => Age::class,
            ],
            'createdAt' => [
                'type' => \DateTime::class,
            ],
            'child' => [
                'type' => Friend::class,
            ],
        ], $schema);
    }

    public function testParseSameHierarchy(): void
    {
        $parser = new Parser();
        $schema = ($parser)(Customer::class);
        $this->assertSame([
            'id' => [
                'type' => 'int',
            ],
            'email' => [
                'type' => 'string',
            ],
            'profile' => [
                'type' => CustomerProfile::class,
            ],
        ], $schema);
    }

    public function testNoTypeHint(): void
    {
        $this->expectException(ParserException::class);
        $parser = new Parser();
        ($parser)(Cat::class);
    }
}
