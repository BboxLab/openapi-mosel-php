<?php

namespace Bboxlab\Moselle\Email;

use Bboxlab\Moselle\Authentication\Authenticator\Authenticator;
use Bboxlab\Moselle\Authentication\Credentials\Credentials;
use Bboxlab\Moselle\Authentication\Token\TokenInterface;
use Bboxlab\Moselle\Authentication\Token\TokenVoter;
use Bboxlab\Moselle\Client\MoselleClient;
use Bboxlab\Moselle\Configuration\ConfigurationInterface;
use Bboxlab\Moselle\Exception\BtHttpBadRequestException;
use Bboxlab\Moselle\Response\Response;
use Bboxlab\Moselle\Validation\Validator;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\Constraints\Email;

class EmailChecker
{
    public function getMoselleClient(): MoselleClient
    {
        return new MoselleClient();
    }

    public function __invoke(
        string $emailAddress,
        ConfigurationInterface $btConfig,
        Credentials $credentials,
        TokenInterface $token = null
    ): Response
    {
        // check validation for input $email
        $validator = new Validator();
        $validator->checkSimpleValidation($emailAddress, [
            new Email(),
        ]);

        // add content to body request
        $options['json'] = ['emailAddress' => $emailAddress];

        // http client declaration
        $client = $this->getMoselleClient();

        // authentication with app credentials flow for getting token if necessary
        if (!$token || !(new TokenVoter())->vote($token)) {
            $token = (new Authenticator($client))->authenticate($btConfig->getOauthAppCredentialsUrl(), $credentials);
        }

        // add token into headers
        $options['auth_bearer'] = $token->getAccessToken();

        // send request and get response
        $response = $client->requestBtOpenApi('POST', $btConfig->getEmailAddressUrl(), $options);

        if (isset($result['status']) && 300 <= $result['status']) {
            throw new BtHttpBadRequestException($result['error']);
        }

        // denormalize into an email output object for validation and validate
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, []);
        $emailOutput = $serializer->denormalize($response, EmailOutput::class);
        $validator->checkObjectValidation($emailOutput);

        // if token and validation outputs are ok, we send it in a response
        return new Response($token, $response);
    }
}

