<?php

declare(strict_types=1);

namespace Helicon\ObjectTypeParser\Tests;

use DateTime;
use Helicon\ObjectTypeParser\Tests\Age\Age;
use Helicon\ObjectTypeParser\Tests\Age\Name as UserName;

class Friend
{
    private int $id;
    private UserName $name;
    private Age $age;
    private DateTime $createdAt;
    private Friend $child;
}
