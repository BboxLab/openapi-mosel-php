<?php

declare(strict_types=1);


namespace Bboxlab\Tests\Portability;

use Bboxlab\Moselle\Client\MoselleClient;
use Bboxlab\Moselle\Configuration\Configuration;
use Bboxlab\Moselle\Email\EmailChecker;
use Bboxlab\Moselle\Email\EmailInput;
use Bboxlab\Moselle\Portability\PortabilityChecker;
use Bboxlab\Moselle\Portability\PortabilityInput;
use Bboxlab\Moselle\Validation\Validator;
use Bboxlab\Tests\Utils\AbstractMoselleTestCase;

class PortabilityCheckerTest extends AbstractMoselleTestCase
{
    public function testCheckPortability()
    {
        // create a mock for Moselle Client
        $mockedClient = $this->createMock(MoselleClient::class);
        $mockedClient->method('requestBtOpenApi')
            ->willReturnOnConsecutiveCalls(
                [
                    'access_token' => '7891011',
                    'expires_in' => 3600
                ],
                [
                    'eligibleForPortability' => true,
                ],
            );

        $checker = new PortabilityChecker(new Validator(), $mockedClient);

        $btConfig = new Configuration();
        $btConfig->setOauthAppCredentialsUrl('https://oauth-fake-url.fr');
        $btConfig->setPortabilityUrl('https://portability-check-fake-url.fr');

        $input = new PortabilityInput();
        $input->setPhoneNumber('+33762106134');
        $input->setRioCode('03P1974840VD');

        $result = $checker($input, $btConfig, $this->createCredentials());

        // test token
        $this->assertEquals(7891011, $result->getToken()->getAccessToken());
        $this->assertEquals(3600, $result->getToken()->getExpiresIn());
        $this->assertEquals(true, $result->getToken()->isNew());
        $this->assertIsString($result->getToken()->getCreatedAt());

        // test content
        $this->assertEquals(true, $result->getContent()['eligibleForPortability']);
    }
}
