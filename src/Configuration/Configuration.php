<?php

namespace Bboxlab\Moselle\Configuration;

class Configuration implements ConfigurationInterface
{
    private string $name;
    private string $emailAddressUrl;
    private string $portabilityUrl;
    private string $ibanUrl;
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

    /**
     * @return string
     */
    public function getPortabilityUrl(): string
    {
        return $this->portabilityUrl;
    }

    /**
     * @param string $portabilityUrl
     */
    public function setPortabilityUrl(string $portabilityUrl): void
    {
        $this->portabilityUrl = $portabilityUrl;
    }

    /**
     * @return string
     */
    public function getIbanUrl(): string
    {
        return $this->ibanUrl;
    }

    /**
     * @param string $ibanUrl
     */
    public function setIbanUrl(string $ibanUrl): void
    {
        $this->ibanUrl = $ibanUrl;
    }

}
