<?php

declare(strict_types=1);

namespace Helicon\ObjectTypeParser\Tests;

class Customer
{
    private int $id;
    private string $email;
    private CustomerProfile $profile;
}
