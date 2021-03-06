<?php

declare(strict_types=1);

namespace Bboxlab\Mosel\Checker;

use Bboxlab\Mosel\Authentication\Authenticator\Authenticator;
use Bboxlab\Mosel\Authentication\Credentials\Credentials;
use Bboxlab\Mosel\Authentication\Token\Token;
use Bboxlab\Mosel\Authentication\Token\TokenVoter;
use Bboxlab\Mosel\Client\MoselClient;
use Bboxlab\Mosel\Dto\BtInputInterface;
use Bboxlab\Mosel\Response\Response;
use Bboxlab\Mosel\Validation\Validator;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

abstract class AbstractChecker
{
    const DEFAULT_RESPONSE_LABEL = 'check';

    public function __construct(
        protected Validator $validator,
        private MoselClient $client,
    ) {}

    public function handleAuthentication(MoselClient $client, string $url, Credentials $credentials, ?Token $token = null): Token
    {
        if (!$token || !(new TokenVoter())->vote($token)) {
            $token = (new Authenticator($client))->authenticate($url, $credentials);
        }

        return $token;
    }

    public function handleRequest(Serializer $serializer, MoselClient $client, Token $token, $input, string $url): array
    {
        $options['auth_bearer'] = $token->getAccessToken();
        $options['json'] = $serializer->normalize($input);

        return $client->requestBtOpenApi('POST', $url, $options);
    }

    public function check(
        string $requestUrl,
        string $oauthUrl,
        BtInputInterface $input,
        string $outputClass,
        ?Credentials $credentials = null,
        Token $token = null,
        string $customResponse = self::DEFAULT_RESPONSE_LABEL
    ): Response
    {
        // validate input
        $this->validator->validate($input);

        // get token and request
        $token = $this->handleAuthentication($this->client, $oauthUrl, $credentials, $token);

        // init serializer
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, []);

        // handle request : if there is no response, we add a generic respons check => true
        if (!$response = $this->handleRequest($serializer, $this->client, $token, $input, $requestUrl, $customResponse)) {
            $response = [
                $customResponse => true
            ];
        }

        // validate output
        if ($outputClass) {
            $objectOutput = $serializer->denormalize($response, $outputClass);
            $this->validator->validate($objectOutput);
        }

        return new Response($token, $response);
    }

}
