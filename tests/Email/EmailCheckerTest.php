<?php

namespace Bboxlab\Tests\Email;

use PHPUnit\Framework\TestCase;
use Bboxlab\Moselle\Email\EmailChecker;

class EmailCheckerTest extends TestCase
{
    public function testCheckEmail()
    {
        $checker = new EmailChecker();
        $this->assertTrue(true);
    }
}
