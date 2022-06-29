<?php

declare(strict_types=1);


namespace Bboxlab\Moselle\Configuration;

use DateTime;

interface ConfigurationInterface
{
    public function getName(): string;
    public function setName(string $name): void;
    public function getEmailAddressUrl(): string;
    public function setEmailAddressUrl(string $emailAddressUrl): void;
    public function getOauthAppCredentialsUrl(): string;
    public function setOauthAppCredentialsUrl(string $oauthAppCredentialsUrl): void;
}
