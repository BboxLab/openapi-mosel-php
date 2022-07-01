<?php

declare(strict_types=1);

namespace Bboxlab\Tests\Authenticator;

use Bboxlab\Moselle\Authentication\Authenticator\Authenticator;
use Bboxlab\Moselle\Authentication\Credentials\Credentials;
use Bboxlab\Moselle\Authentication\Token\Token;
use Bboxlab\Moselle\Exception\BtHttpBadRequestException;
use Bboxlab\Tests\Utils\AbstractMoselleTestCase;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class AuthenticatorTest extends AbstractMoselleTestCase
{
    public function createBtToken($new=true, $token="12345", $expiresIn=3600)
    {
        $expectedResponse = new Token();
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
        $credentials = $this->createCredentials();

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

    public function testDenormalizeResponse()
    {
        $mockedClient = $this->createMoselleMock();
        $authenticator = new Authenticator($mockedClient);

        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, []);

        $response = [
            "access_token" => "at-894db2cd-a118-42e6-9f87-9b17cb5360d5",
            "expires_in" => 3600,
            "token_type" => "Bearer",
            "refresh_credit" => 0,
            "scope" => "EXT_PostalAddressConsult EXT_SalesPartnerContext EXT_ShoppingCartManage openid EXT_IbanConsult roles profile DocumentConsult EXT_CustomerAccountManage EXT_EmailAddressConsult EXT_PortabilityConsult EXT_OrderManage"
        ];

        $token = $authenticator->denormalizeResponse($serializer, $response);

        // test by comparing result between authenticator->authenticate response and expected response
        $this->assertEquals($response['access_token'], $token->getAccessToken());
        $this->assertTrue($token->isNew());
        $this->assertEquals($response['expires_in'], $token->getExpiresIn());
        $this->assertIsString($token->getCreatedAt());
    }
}
