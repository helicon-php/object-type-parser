<?php

declare(strict_types=1);

namespace Helicon\ObjectTypeParser\Tests;

use DateTime;
use Helicon\ObjectTypeParser\Tests\Age\Age;
use Helicon\ObjectTypeParser\Tests\Age\Name as UserName;

class Friend
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var UserName
     */
    private $name;

    /**
     * @var Age
     */
    private $age;

    /**
     * @var DateTime
     */
    private $createdAt;

    /**
     * @var Friend
     */
    private $child;
}
