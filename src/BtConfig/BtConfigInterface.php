<?php

declare(strict_types=1);


namespace Bboxlab\Moselle\BtConfig;

use DateTime;

interface BtConfigInterface
{
    public function getName(): string;
    public function setName(string $name): void;
    public function getPortabilityUrl(): string;
    public function setPortabilityUrl(string $portabilityUrl): void;
    public function getPostalAddressUrl(): string;
    public function setPostalAddressUrl(string $postalAddressUrl): void;
    public function getEmailAddressUrl(): string;
    public function setEmailAddressUrl(string $emailAddressUrl): void;
    public function getIbanUrl(): string;
    public function setIbanUrl(string $ibanUrl): void;
    public function getCartUrl(): string;
    public function setCartUrl(string $cartUrl): void;
    public function getOrderUrl(): string;
    public function setOrderUrl(string $orderUrl): void;
    public function getCustomerUrl(): string;
    public function setCustomerUrl(string $customerUrl): void;
    public function getOauthAppCredentialsUrl(): string;
    public function setOauthAppCredentialsUrl(string $oauthAppCredentialsUrl): void;
    public function getOauthAuthorizationCodeUrl(): string;
    public function setOauthAuthorizationCodeUrl(string $oauthAuthorizationCodeUrl): void;
    public function getUserInfoUrl(): string;
    public function setUserInfoUrl(string $userInfoUrl): void;
    public function getOauthAppCredentialsClientId(): string;
    public function setOauthAppCredentialsClientId(string $oauthAppCredentialsClientId): void;
    public function getOauthAppCredentialsSecret(): string;
    public function setOauthAppCredentialsSecret(string $oauthAppCredentialsSecret): void;
    public function getBtConnectionPageUrl(): string;
    public function setBtConnectionPageUrl(string $btConnectionPageUrl): void;
    public function getBtForgottenPasswordUrl(): string;
    public function setBtForgottenPasswordUrl(string $btForgottenPasswordUrl): void;
    public function getBtLogoutUrl(): string;
    public function setBtLogoutUrl(string $btLogoutUrl): void;
    public function isActive(): bool;
    public function setActive(bool $active): void;
}
