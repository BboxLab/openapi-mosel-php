<?php

namespace Bboxlab\Tests\Authenticator;

use Bboxlab\Moselle\Authentication\Authenticator\Authenticator;
use Bboxlab\Moselle\Authentication\Credentials\Credentials;
use Bboxlab\Moselle\Authentication\Token\BtToken;
use Bboxlab\Moselle\Client\MoselleClient;
use Bboxlab\Moselle\Exception\BtHttpBadRequestException;
use PHPUnit\Framework\TestCase;

class AuthenticatorTest extends TestCase
{
    private function createMoselleMock(int $token = 12345, $expiresIn = 3600)
    {
        $mockedClient = $this->createMock(MoselleClient::class);
        $mockedClient->method('requestBtOpenApi')
            ->willReturn([
                'access_token' => $token,
                'expires_in' => $expiresIn
            ]);
        return $mockedClient;
    }

    public function createBtToken($new=true, $token=12345, $expiresIn=3600)
    {
        $expectedResponse = new BtToken();
        $expectedResponse->setNew($new);
        $expectedResponse->setCreatedAt((new \DateTime())->format('Y-m-d H:i:s p'));
        $expectedResponse->setAccessToken($token);
        $expectedResponse->setExpiresIn($expiresIn);

        return $expectedResponse;
    }

    /** nominal case */
    public function testAuthenticate()
    {
        // create an authenticator with a mocked moselle client
        $mockedClient = $this->createMoselleMock();

        $authenticator = new Authenticator($mockedClient);
        $credentials = new Credentials();
        $credentials->setUsername('fake_user');
        $credentials->setPassword('fake_password');
        $response = $authenticator->authenticate('https://fakeurl.fake', $credentials);

        // create the expected response
        $expectedResponse = $this->createBtToken();

        // test by comparing result between authenticator->authenticate response and expected response
        $this->assertEquals($expectedResponse->getAccessToken(), $response->getAccessToken());
        $this->assertEquals($expectedResponse->isNew(), $response->isNew());
        $this->assertEquals($expectedResponse->getExpiresIn(), $response->getExpiresIn());
        $this->assertIsString($response->getCreatedAt());
    }

    /** pass credentials as an array */
    public function testAuthenticateWithWrongFormatCredentials()
    {
        // create an authenticator with a mocked moselle client
        $mockedClient = $this->createMoselleMock();
        $authenticator = new Authenticator($mockedClient);

        // we simulate an error type
        $this->expectException(\TypeError::class);
        $authenticator->authenticate('https://fakeurl.fake', ['fake_user', 'fake_password']);
    }

    /** pass a string  but it's not an url*/
    public function testAuthenticateWithWrongUrl()
    {
        // create an authenticator with a mocked moselle client
        $mockedClient = $this->createMoselleMock();
        $authenticator = new Authenticator($mockedClient);

        $credentials = new Credentials();
        $credentials->setUsername('fake_user');
        $credentials->setPassword('fake_password');

        // should return an exception
        $this->expectException(BtHttpBadRequestException::class);
        $authenticator->authenticate('hello world', $credentials);
    }

    /** pass credentials as an object but put a 200 char string as password */
    public function testAuthenticateWithWrongFormatTooLongUsername()
    {
        // create an authenticator with a mocked moselle client
        $mockedClient = $this->createMoselleMock();
        $authenticator = new Authenticator($mockedClient);

        $credentials = new Credentials();
        $credentials->setUsername('fake_user');
        $credentials->setPassword('Nous étions à l’étude, quand le Proviseur entra, suivi d’un nouveau habillé en bourgeois et d’un garçon de classe qui portait un grand pupitre. Ceux qui dormaient se réveillèrent, et chacun se leva comme surpris dans son travail.');

        // should return an exception
        $this->expectException(BtHttpBadRequestException::class);
        $authenticator->authenticate('https://fakeurl.fake', $credentials);
    }
}
