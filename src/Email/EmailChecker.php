<?php

namespace Bboxlab\Moselle\Email;

use Bboxlab\Moselle\Authentication\Authenticator\Authenticator;
use Bboxlab\Moselle\Authentication\Credentials\Credentials;
use Bboxlab\Moselle\Authentication\Token\BtToken;
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
        BtToken $token = null
    ): Response
    {
        // add content to body request
        $options['json'] = ['emailAddress' => $emailAddress];

        // authentication with app credentials flow for getting token if necessary
        if (!$token || !(new TokenVoter())->vote($token)) {
            $client = new MoselleClient();
            $token = (new Authenticator($client))->authenticate($btConfig->getOauthAppCredentialsUrl(), $credentials);
        }

        $options['auth_bearer'] = $token->getAccessToken();

        // get response
        $emailResponse = $client->requestBtOpenApi('POST', $btConfig->getEmailAddressUrl(), $options);

        if (isset($result['status']) && 300 <= $result['status']) {
            throw new BtHttpBadRequestException($result['error']);
        }

        return new Response($token, $emailResponse);
    }
}

