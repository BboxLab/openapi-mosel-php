<?php

namespace Bboxlab\Moselle\Email;

use Bboxlab\Moselle\Client\MoselleClient;
use Bboxlab\Moselle\Exception\BouyguesHttpBadRequestException;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class EmailChecker
{
    const EMAIL_CHECK_URL = 'https://open.api.bouyguestelecom.fr/v1/customer-management/email-addresses/check';

    public function __invoke(
        string $emailAddress,
        $authenticator,
        HttpClientInterface $client,
        string $url = self::EMAIL_CHECK_URL
    ): array
    {
        // add content to body request
        $options['json'] = ['emailAddress' => $emailAddress];

        // authentication with app credentials flow: get token
        $options['auth_bearer'] = $authenticator->authenticate();

        // get response
        $result = (new MoselleClient())->request($client, 'POST', $url, $options);

        if (isset($result['status']) && 300 <= $result['status']) {
            throw new BouyguesHttpBadRequestException($result['error']);
        }

        return $result;
    }
}

