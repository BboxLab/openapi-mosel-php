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


final class EmailChecker
{
    public function __invoke(
        string $emailAddress,
        ConfigurationInterface $btConfig,
        Credentials $credentials,
        TokenInterface $token = null
    ): Response
    {
        // add content to body request
        $options['json'] = ['emailAddress' => $emailAddress];

        // http client declaration
        $client = new MoselleClient();

        // authentication with app credentials flow for getting token if necessary
        if (!$token || !(new TokenVoter())->vote($token)) {
            $token = (new Authenticator($client))->authenticate($btConfig->getOauthAppCredentialsUrl(), $credentials);
        }

        // add token into headers
        $options['auth_bearer'] = $token->getAccessToken();

        // send request and get response
        $emailResponse = $client->requestBtOpenApi('POST', $btConfig->getEmailAddressUrl(), $options);

        // todo: check email response format

        if (isset($result['status']) && 300 <= $result['status']) {
            throw new BtHttpBadRequestException($result['error']);
        }

        return new Response($token, $emailResponse);
    }
}

