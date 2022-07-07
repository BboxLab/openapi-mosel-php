<?php

namespace Bboxlab\Mosel\Configuration;

class ConfigurationCreator
{
    CONST DEFAULT_BT_ENV_NAME = 'ap4';

    public function createProdConfig(): Configuration
    {
        $configuration = new Configuration();
        $configuration->setOauthAppCredentialsUrl('https://oauth2.bouyguestelecom.fr/token?grant_type=client_credentials');
        $configuration->setEmailAddressUrl('https://open.api.bouyguestelecom.fr/v1/customer-management/email-addresses/check');

        return $configuration;
    }

    /** AP is the name given by bt for its sandbox envs */
    public function createApConfig(string $name = self::DEFAULT_BT_ENV_NAME): Configuration
    {
        $configuration = new Configuration();
        $configuration->setOauthAppCredentialsUrl('https://oauth2.sandbox.bouyguestelecom.fr/'.$name.'/token?grant_type=client_credentials');
        $configuration->setEmailAddressUrl('https://open.api.sandbox.bouyguestelecom.fr/'.$name.'/v1/customer-management/email-addresses/check');

        return $configuration;
    }
}
