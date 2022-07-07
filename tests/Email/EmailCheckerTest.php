<?php

namespace Bboxlab\Tests\Email;

use Bboxlab\Mosel\Client\MoselClient;
use Bboxlab\Mosel\Configuration\Configuration;
use Bboxlab\Mosel\Email\EmailInput;
use Bboxlab\Mosel\Validation\Validator;
use Bboxlab\Tests\Utils\AbstractMoselTestCase;
use Bboxlab\Mosel\Email\EmailChecker;

class EmailCheckerTest extends AbstractMoselTestCase
{
    public function testCheckEmail()
    {
        // create a mock for Mosel Client
        $mockedClient = $this->createMock(MoselClient::class);
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

        $checker = new EmailChecker(new Validator(), $mockedClient);

        $btConfig = new Configuration();
        $btConfig->setOauthAppCredentialsUrl('http://oauth-fake-url.fr');
        $btConfig->setEmailAddressUrl('http://emailcheck-fake-url.fr');

        $input = new EmailInput();
        $input->setEmailAddress('eugenie.grandet@balzac.fr');

        $result = $checker($input, $btConfig, $this->createCredentials());

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
