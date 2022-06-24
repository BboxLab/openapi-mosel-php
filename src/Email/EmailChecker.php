<?php

namespace Bboxlab\Moselle\Email;

class EmailChecker
{
    const EMAIL_CHECK_URL = 'https://open.api.bouyguestelecom.fr/v1/customer-management/email-addresses/check';

    public function __invoke(string $emailAddress, $authenticator, $client, string $url = self::EMAIL_CHECK_URL): array
    {
        // add content to body request
        $options['json'] = ['emailAddress' => $emailAddress];

        // authentication with app credentials flow: get token
        $options['auth_bearer'] = $authenticator->authenticate();

        // get response
        $response = $client('POST', $url, $options);

        return $response->toArray();
    }
}

