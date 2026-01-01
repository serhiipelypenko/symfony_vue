<?php

namespace App\Tests\Unit\Utils\Generator;

use App\Utils\Generator\PasswordGenerator;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group('unit')]
class PasswordGeneratorTest extends TestCase
{
    public function testGeneratePassword(): void
    {
        $password = PasswordGenerator::generatePassword(8);

        self::assertSame(8, strlen($password));
    }
}
