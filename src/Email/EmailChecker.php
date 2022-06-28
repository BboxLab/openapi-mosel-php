<?php

namespace Bboxlab\Moselle\Email;

use Bboxlab\Moselle\Client\MoselleClient;
use Bboxlab\Moselle\Exception\BouyguesHttpBadRequestException;


final class EmailChecker
{
    const EMAIL_CHECK_URL = 'https://open.api.bouyguestelecom.fr/v1/customer-management/email-addresses/check';

    public function __invoke(
        string $emailAddress,
        string $url = self::EMAIL_CHECK_URL
    ): array
    {
        // add content to body request
        $options['json'] = ['emailAddress' => $emailAddress];

        // authentication with app credentials flow: get token
        $client = new MoselleClient();
//        $options['auth_bearer'] = (new Authenticator($client))->authenticate();

        // get response
        $result = $client->requestBtOpenApi('POST', $url, $options);

        if (isset($result['status']) && 300 <= $result['status']) {
            throw new BouyguesHttpBadRequestException($result['error']);
        }

        return $result;
    }
}

