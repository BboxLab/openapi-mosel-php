<?php

namespace Bboxlab\Moselle\Authentication\Authenticator;

use Bboxlab\Moselle\Authentication\Credentials\Credentials;
use Bboxlab\Moselle\Authentication\Token\BtToken;
use Bboxlab\Moselle\Client\MoselleClient;
use Bboxlab\Moselle\Exception\BtHttpBadRequestException;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\Constraints\Url;
use Symfony\Component\Validator\Validation;

class Authenticator
{
    const OAUTH_CREDENTIALS_URL = 'https://oauth2.bouyguestelecom.fr/token?grant_type=client_credentials';

    public function __construct(private MoselleClient $client) {}

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

    public function checkSimpleValidation($input, $rules)
    {
        $validator = Validation::createValidator();
        $violations = $validator->validate($input, $rules);

        if (0 !== count($violations)) {
            $errorsString = (string) $violations;
            throw new BtHttpBadRequestException($errorsString);
        }
    }

    public function checkObjectValidation($objectToValidate)
    {
        $validator = Validation::createValidatorBuilder()
            ->enableAnnotationMapping()
            ->addDefaultDoctrineAnnotationReader()
            ->getValidator()
        ;

        $violations = $validator->validate($objectToValidate);

        if (0 !== count($violations)) {
            $errorsString = (string) $violations;
            throw new BtHttpBadRequestException($errorsString);
        }
    }

    public function authenticate(
        string $oauthCredentialsUrl = self::OAUTH_CREDENTIALS_URL,
        Credentials $credentials = null
    ): BtToken
    {
        // validate credentials as input
        $this->checkObjectValidation($credentials);

        // validate url as input
        $this->checkSimpleValidation($oauthCredentialsUrl, [
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
        $token = $serializer->denormalize($response, BtToken::class);
        $datetime = new \DateTime();
        $datetime = $datetime->format('Y-m-d H:i:s p');
        $token->setCreatedAt($datetime);
        $token->setNew(true);

        // the validity of the token object is checked
        $this->checkObjectValidation($token);

        return $token;
    }
}
