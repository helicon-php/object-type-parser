<?php


namespace Helicon\ObjectTypeParser\Tests;


use Helicon\ObjectTypeParser\Tests\Age\Age;
use Helicon\ObjectTypeParser\Tests\Age\Name as UserName;
use DateTime;

class Dog
{
    private int $id;

    private UserName $name;

    private Age $age;

    private DateTime $createdAt;

    private Dog $child;

    private self $child2;
}