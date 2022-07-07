<?php

declare(strict_types=1);

namespace Bboxlab\Moselle\Portability;

use Bboxlab\Moselle\Authentication\Credentials\Credentials;
use Bboxlab\Moselle\Authentication\Token\TokenInterface;
use Bboxlab\Moselle\Checker\AbstractChecker;
use Bboxlab\Moselle\Configuration\ConfigurationInterface;
use Bboxlab\Moselle\Dto\BtInputInterface;
use Bboxlab\Moselle\Response\Response;

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
