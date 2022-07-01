<?php

namespace Bboxlab\Moselle\Authentication\Authenticator;

use Bboxlab\Moselle\Authentication\Credentials\Credentials;
use Bboxlab\Moselle\Authentication\Token\Token;
use Bboxlab\Moselle\Client\MoselleClient;
use Bboxlab\Moselle\Exception\BtHttpBadRequestException;
use Bboxlab\Moselle\Validation\Validator;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\Constraints\Url;

class Authenticator
{
    const OAUTH_CREDENTIALS_URL = 'https://oauth2.bouyguestelecom.fr/token?grant_type=client_credentials';

    public function __construct(private MoselleClient $client) {}

    public function denormalizeResponse(Serializer $serializer, array $response): Token
    {
        $token = $serializer->denormalize($response, Token::class);
        $datetime = new \DateTime();
        $datetime = $datetime->format('Y-m-d H:i:s p');
        $token->setCreatedAt($datetime);
        $token->setNew(true);

        return $token;
    }

    public function postCrendentials(string $url, Credentials $credentials): array
    {
        return $this->client->requestBtOpenApi('POST', $url, [
            'auth_basic' => [
                $credentials->getUsername(),
                $credentials->getPassword()
            ],
            'query' => [
                'grant_type' => 'client_credentials'
            ]
        ]);
    }

    public function authenticate(
        string $oauthCredentialsUrl = self::OAUTH_CREDENTIALS_URL,
        Credentials $credentials = null
    ): Token
    {
        // validate credentials as input
        $validator = new Validator();
        $validator->checkObjectValidation($credentials);

        // validate url as input
        $validator->checkSimpleValidation($oauthCredentialsUrl, [
            new Url(),
        ]);

        // client fetches BT API with credentials for getting a token
        try {
            $response = $this->postCrendentials($oauthCredentialsUrl, $credentials);
        } catch (\Exception $e) {
            throw new BtHttpBadRequestException('[Bt Authenticator] ' . $e->getMessage());
        }

        // the response is deserialized
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, []);
        $token = $this->denormalizeResponse($serializer, $response);

        // the validity of the token is checked
        $validator->checkObjectValidation($token);

        return $token;
    }
}
