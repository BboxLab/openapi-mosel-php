<?php

declare(strict_types=1);

namespace Bboxlab\Tests\Iban;


use Bboxlab\Mosel\Client\MoselClient;
use Bboxlab\Mosel\Configuration\Configuration;
use Bboxlab\Mosel\Email\EmailInput;
use Bboxlab\Mosel\Iban\IbanChecker;
use Bboxlab\Mosel\Iban\IbanInput;
use Bboxlab\Mosel\Validation\Validator;
use Bboxlab\Tests\Utils\AbstractMoselTestCase;

class IbanCheckerTest extends AbstractMoselTestCase
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
                []
            );

        $checker = new IbanChecker(new Validator(), $mockedClient);

        $btConfig = new Configuration();
        $btConfig->setOauthAppCredentialsUrl('http://oauth-fake-url.fr');
        $btConfig->setIbanUrl('http://ibancheck-fake-url.fr');

        $input = new IbanInput();
        $input->setIban('FR7630001007941234567890185');

        $result = $checker($input, $btConfig, $this->createCredentials());

        // test token
        $this->assertEquals(123456, $result->getToken()->getAccessToken());
        $this->assertEquals(3600, $result->getToken()->getExpiresIn());
        $this->assertEquals(true, $result->getToken()->isNew());
        $this->assertIsString($result->getToken()->getCreatedAt());

        // test content
        $this->assertEquals(true,$result->getContent()['iban']);
    }
}
