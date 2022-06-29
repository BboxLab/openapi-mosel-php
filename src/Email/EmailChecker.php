<?php

namespace Bboxlab\Moselle\Email;

use Bboxlab\Moselle\Authenticator\Authenticator;
use Bboxlab\Moselle\Authenticator\Credentials;
use Bboxlab\Moselle\Configuration\Configuration;
use Bboxlab\Moselle\Client\MoselleClient;
use Bboxlab\Moselle\Exception\BouyguesHttpBadRequestException;

final class EmailChecker
{
    public function __invoke(
        string        $emailAddress,
        Configuration $btConfig,
        Credentials   $credentials,
                      $token = null
    ): array
    {
        // add content to body request
        $options['json'] = ['emailAddress' => $emailAddress];

        // authentication with app credentials flow: get token
        // todo: get token, only if there is no token or if given token is not valid
        $client = new MoselleClient();
        $options['auth_bearer'] = (new Authenticator($client))->authenticate($btConfig->getOauthAppCredentialsUrl(), $credentials, $token);

        // get response
        $result = $client->requestBtOpenApi('POST', $btConfig->getEmailAddressUrl(), $options);

        if (isset($result['status']) && 300 <= $result['status']) {
            throw new BouyguesHttpBadRequestException($result['error']);
        }

        return $result;
    }
}

