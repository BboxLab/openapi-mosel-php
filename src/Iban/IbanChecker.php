<?php

declare(strict_types=1);


namespace Bboxlab\Mosel\Iban;


use Bboxlab\Mosel\Authentication\Credentials\Credentials;
use Bboxlab\Mosel\Authentication\Token\TokenInterface;
use Bboxlab\Mosel\Checker\AbstractChecker;
use Bboxlab\Mosel\Configuration\ConfigurationInterface;
use Bboxlab\Mosel\Dto\BtInputInterface;
use Bboxlab\Mosel\Response\Response;

class IbanChecker extends AbstractChecker
{
    public function __invoke(
        BtInputInterface $input,
        ConfigurationInterface $btConfig,
        Credentials $credentials,
        TokenInterface $token = null,
    ): Response
    {
        return $this->check(
            $btConfig->getIbanUrl(),
            $btConfig->getOauthAppCredentialsUrl(),
            $input,
            IbanOutput::class,
            $credentials,
            $token
        );
    }
}
