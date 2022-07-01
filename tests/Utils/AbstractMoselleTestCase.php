<?php

declare(strict_types=1);

namespace Bboxlab\Tests\Utils;

use Bboxlab\Moselle\Authentication\Credentials\Credentials;
use Bboxlab\Moselle\Client\MoselleClient;
use PHPUnit\Framework\TestCase;

abstract class AbstractMoselleTestCase extends TestCase
{
    protected function createMoselleMock(string $token = "12345", $expiresIn = 3600)
    {
        $mockedClient = $this->createMock(MoselleClient::class);
        $mockedClient->method('requestBtOpenApi')
            ->willReturn([
                'access_token' => $token,
                'expires_in' => $expiresIn
            ]);
        return $mockedClient;
    }

    protected function createCredentials($username='fake_user', $password='fake_password'): Credentials
    {
        $credentials = new Credentials();
        $credentials->setUsername($username);
        $credentials->setPassword($password);

        return $credentials;
    }

}
