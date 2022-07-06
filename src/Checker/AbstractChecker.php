<?php

declare(strict_types=1);

namespace Bboxlab\Moselle\Checker;

use Bboxlab\Moselle\Authentication\Authenticator\Authenticator;
use Bboxlab\Moselle\Authentication\Credentials\Credentials;
use Bboxlab\Moselle\Authentication\Token\Token;
use Bboxlab\Moselle\Authentication\Token\TokenVoter;
use Bboxlab\Moselle\Client\MoselleClient;
use Bboxlab\Moselle\Dto\BtInputInterface;
use Bboxlab\Moselle\Email\EmailInput;
use Bboxlab\Moselle\Email\EmailOutput;
use Bboxlab\Moselle\Portability\PortabilityInput;
use Bboxlab\Moselle\Portability\PortabilityOutput;
use Bboxlab\Moselle\Response\Response;
use Bboxlab\Moselle\Validation\Validator;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

abstract class AbstractChecker
{
    public function __construct(
        protected Validator $validator,
        private MoselleClient $client,
    ) {}

    public function handleAuthentication(MoselleClient $client, string $url, Credentials $credentials, ?Token $token = null): Token
    {
        if (!$token || !(new TokenVoter())->vote($token)) {
            $token = (new Authenticator($client))->authenticate($url, $credentials);
        }

        return $token;
    }

    public function handleRequest(Serializer $serializer, MoselleClient $client, Token $token, $input, string $url): array
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
        Token $token = null
    ): Response
    {
        // validate input
        $this->validator->validate($input);

        // get token and request
        $token = $this->handleAuthentication($this->client, $oauthUrl, $credentials, $token);

        // init serializer
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, []);

        // handle request
        $response = $this->handleRequest($serializer, $this->client, $token, $input, $requestUrl);

        // validate output
        $objectOutput = $serializer->denormalize($response, $outputClass);
        $this->validator->validate($objectOutput);

        return new Response($token, $response);
    }

}
