<?php

namespace Bboxlab\Moselle\Configuration;

class Configuration implements ConfigurationInterface
{
    private string $name;
    private string $emailAddressUrl;
    private string $oauthAppCredentialsUrl;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getEmailAddressUrl(): string
    {
        return $this->emailAddressUrl;
    }

    /**
     * @param string $emailAddressUrl
     */
    public function setEmailAddressUrl(string $emailAddressUrl): void
    {
        $this->emailAddressUrl = $emailAddressUrl;
    }

    /**
     * @return string
     */
    public function getOauthAppCredentialsUrl(): string
    {
        return $this->oauthAppCredentialsUrl;
    }

    /**
     * @param string $oauthAppCredentialsUrl
     */
    public function setOauthAppCredentialsUrl(string $oauthAppCredentialsUrl): void
    {
        $this->oauthAppCredentialsUrl = $oauthAppCredentialsUrl;
    }

}