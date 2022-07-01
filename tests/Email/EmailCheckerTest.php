<?php

namespace Bboxlab\Tests\Email;

use Bboxlab\Moselle\Client\MoselleClient;
use Bboxlab\Moselle\Configuration\Configuration;
use Bboxlab\Tests\Utils\AbstractMoselleTestCase;
use Bboxlab\Moselle\Email\EmailChecker;

class EmailCheckerTest extends AbstractMoselleTestCase
{
    public function testCheckEmail()
    {
        // create a mock for Moselle Client
        $mockedClient = $this->createMock(MoselleClient::class);
        $mockedClient->method('requestBtOpenApi')
            ->willReturnOnConsecutiveCalls(
                [
                    'access_token' => '123456',
                    'expires_in' => 3600
                ],
                [
                    'contactEmailAddress' => false,
                    'validEmailAddress' => true
                ],
            );

        // replace getMoselleClient method only by returning a mocked client
        $mockedChecker = $this->getMockBuilder(EmailChecker::class)
            ->onlyMethods(['getMoselleClient'])
            ->getMock();

        // inject mocked client into getMoselleClient function (inside EmailChecker)
        $mockedChecker->method('getMoselleClient')
            ->willReturn($mockedClient);

        $btConfig = new Configuration();
        $btConfig->setOauthAppCredentialsUrl('http://oauth-fake-url.fr');
        $btConfig->setEmailAddressUrl('http://emailcheck-fake-url.fr');

        $result = $mockedChecker('eugenie.grandet@balzac.fr', $btConfig, $this->createCredentials());

        // test token
        $this->assertEquals(123456, $result->getToken()->getAccessToken());
        $this->assertEquals(3600, $result->getToken()->getExpiresIn());
        $this->assertEquals(true, $result->getToken()->isNew());
        $this->assertIsString($result->getToken()->getCreatedAt());

        // test content
        $this->assertEquals(false,$result->getContent()['contactEmailAddress']);
        $this->assertEquals(true, $result->getContent()['validEmailAddress']);
    }
}
