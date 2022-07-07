<?php

declare(strict_types=1);

namespace Bboxlab\Mosel\Portability;

use Bboxlab\Mosel\Authentication\Credentials\Credentials;
use Bboxlab\Mosel\Authentication\Token\TokenInterface;
use Bboxlab\Mosel\Checker\AbstractChecker;
use Bboxlab\Mosel\Configuration\ConfigurationInterface;
use Bboxlab\Mosel\Dto\BtInputInterface;
use Bboxlab\Mosel\Response\Response;

class PortabilityChecker extends AbstractChecker
{
    public function __invoke(
        BtInputInterface $input,
        ConfigurationInterface $btConfig,
        Credentials $credentials,
        TokenInterface $token = null,
    ): Response
    {
        return $this->check(
            $btConfig->getPortabilityUrl(),
            $btConfig->getOauthAppCredentialsUrl(),
            $input,
            PortabilityOutput::class,
            $credentials,
            $token
        );
    }

}
