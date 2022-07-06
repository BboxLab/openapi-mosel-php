<?php

namespace Bboxlab\Moselle\Sdk;

use Bboxlab\Moselle\Authentication\Credentials\Credentials;
use Bboxlab\Moselle\Authentication\Token\Token;
use Bboxlab\Moselle\Configuration\Configuration;
use Bboxlab\Moselle\Configuration\ConfigurationInterface;
use Bboxlab\Moselle\Email\EmailInput;
use Bboxlab\Moselle\Portability\PortabilityInput;
use Bboxlab\Moselle\Response\Response;

interface SdkInterface
{
    public function __construct(Credentials $credentials, ConfigurationInterface $configuration);
    public function getConfiguration(): ?Configuration;
    public function getCredentials(): ?Credentials;
    public function checkEmail(EmailInput $input, ?Token $token = null): Response;
    public function checkPortability(PortabilityInput $input, ?Token $token = null): Response;

}
