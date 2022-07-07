<?php

declare(strict_types=1);

namespace Bboxlab\Tests\Utils;

use Bboxlab\Mosel\Authentication\Credentials\Credentials;
use Bboxlab\Mosel\Client\MoselClient;
use PHPUnit\Framework\TestCase;

abstract class AbstractMoselTestCase extends TestCase
{
    protected function createMoselMock(string $token = "12345", $expiresIn = 3600)
    {
        $mockedClient = $this->createMock(MoselClient::class);
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
