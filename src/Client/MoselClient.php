<?php

namespace Bboxlab\Mosel\Client;

use Bboxlab\Mosel\Exception\BtHttpBadRequestException;
use Symfony\Component\HttpClient\DecoratorTrait;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class MoselClient implements HttpClientInterface
{
    use DecoratorTrait;

    public function requestBtOpenApi(string $method, string $url, array $options = []): array
    {
        $response = $this->client->request($method, $url, $options);

        // if there is no response we return an empty array, for example when it's a 204 response
        if (Response::HTTP_NO_CONTENT == $response->getStatusCode()) {
            return [];
        }

        if ($code = $response->getStatusCode() >= Response::HTTP_MULTIPLE_CHOICES) {
            $this->handleBtRequestError($code, $response->toArray(false));
        }

        return $response->toArray();
    }

    /**
     * handle bt error, the bt error format is "error", "error_description", "error_parameters"
     */
    private function handleBtRequestError(int $code, array $error = [])
    {
        $name = 'Unknown error';
        $description = 'No description has been returned by bt API.';
        $parameters = [];

        if (isset($error['error'])) {
            $name = $error['error'];
        };

        if (isset($error['error_description'])) {
            $description = $error['error_description'];
        };

        if (isset($error['error_parameters'])) {
            $parameters = $error['error_parameters'];
        }

        throw new BtHttpBadRequestException(
            $name,
            null,
            $code,
            $description,
            $parameters,
            BtHttpBadRequestException::BT_SOURCE
        );
    }

//    /**
//     * For using bt api in test we need to add some headers
//     * from Mosel
//     *
//     * @throws BtHttpBadRequestException
//     */
//    protected function addBouyguesTestHeaders(array $options, string $url):array
//    {
//        if (str_contains($url, '.sandbox.'))
//        {
//            if (!isset($options['headers']))
//                $options['headers'] = [];
//            $options['headers']['x-version'] = 4;
//
//            // depending the bt test env, headers values need to be changed
//            if (str_contains($url,'ap4')) {
//                $options['headers']['x-banc'] = 'ap23';
//            } else if (str_contains($url,'ap3')){
//                $options['headers']['x-banc'] = 'ap21';
//            } else {
//                throw new BtHttpBadRequestException(
//                    'No corresponding "AP" env has been found in bt url oauth test url. "AP" known env are: AP3 and AP4'
//                );
//            }
//        }
//
//        return $options;
//    }
}
