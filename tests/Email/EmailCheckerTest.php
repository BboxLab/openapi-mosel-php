<?php

namespace Bboxlab\Tests\Email;

use Bboxlab\Moselle\Configuration\Configuration;
use Bboxlab\Tests\Utils\AbstractMoselleTestCase;
use Bboxlab\Moselle\Email\EmailChecker;

class EmailCheckerTest extends AbstractMoselleTestCase
{
    public function testCheckEmail()
    {
        $mockedClient = $this->createMoselleMock();

        $mockedChecker = $this->createMock(EmailChecker::class);
        $mockedChecker->method('getMoselleClient')
            ->willReturn($mockedClient);

        $btConfig = new Configuration();

        $result = $mockedChecker('eugenie.grandet@balzac.fr', $btConfig, $this->createCredentials());

        // mock client
        $this->assertTrue(true);
    }
}
