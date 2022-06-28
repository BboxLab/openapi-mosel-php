<?php

namespace Bboxlab\Moselle\Authenticator;

use Bboxlab\Moselle\Exception\BouyguesHttpBadRequestException;
use Bboxlab\Moselle\Client\MoselleClient;

class Authenticator
{
    const OAUTH_CREDENTIALS_URL = 'https://oauth2.bouyguestelecom.fr/token?grant_type=client_credentials';

    public function __construct(private MoselleClient $client) {}

    private function postCrendentials(string $url, Credentials $credentials): array
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

    /**
     * @throws BouyguesHttpBadRequestException
     */
    public function authenticate(string $oauthCredentialsUrl = self::OAUTH_CREDENTIALS_URL, Credentials $credentials = null, $token = null): string
    {
        // check if token is valid
        if ($token) {
//            todo: complete
//            if token is valid return token value
        }

        // if there is no token or if token is not valid, post for a new token
        try {
            $response = $this->postCrendentials($oauthCredentialsUrl, $credentials);
            return $response['access_token'];
        } catch (\Exception $e) {
            throw new BouyguesHttpBadRequestException('[Bt Authenticator] ' . $e->getMessage());
        }

//        if (!$token = $this->findToken($type, $shopUser)) {
//            // it there is no token and it's code authorization, we return an error
//            // user must log again to create a new token with bt widget
//            if (BouyguesToken::CODE_AUTHORIZATION_TYPE == $type) {
//                throw new BouyguesInvalidCustomerTokenException('No valid customer token has been found. You need to log again in bt.');
//            }
//
//            // it there is no token and it's not code authorization, we fetch open api for a creating new one and save it
//            try {
//                $response = $this->post($this->elbaConfigRetriever->getInfoByType(BtConfigEnum::OAUTH_CREDENTIALS_UR_GETTER));
//                $token = $response['access_token'];
//            } catch (\Exception $e) {
//                throw new BouyguesHttpBadRequestException('[Elba Authenticator] ' . $e->getMessage());
//            }
//
//            //new fetched token is saved in database
//            $this->saveAppToken($response);
//            return $token;
//        }

//        return $token->getValue();
    }
}
