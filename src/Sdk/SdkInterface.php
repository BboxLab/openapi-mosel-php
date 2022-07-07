<?php

namespace Bboxlab\Mosel\Sdk;

use Bboxlab\Mosel\Authentication\Credentials\Credentials;
use Bboxlab\Mosel\Authentication\Token\Token;
use Bboxlab\Mosel\Configuration\Configuration;
use Bboxlab\Mosel\Configuration\ConfigurationInterface;
use Bboxlab\Mosel\Email\EmailInput;
use Bboxlab\Mosel\Iban\IbanInput;
use Bboxlab\Mosel\Portability\PortabilityInput;
use Bboxlab\Mosel\Response\Response;

interface SdkInterface
{
    public function __construct(Credentials $credentials, ConfigurationInterface $configuration);
    public function getConfiguration(): ?Configuration;
    public function getCredentials(): ?Credentials;
    public function checkEmail(EmailInput $input, ?Token $token = null): Response;
    public function checkPortability(PortabilityInput $input, ?Token $token = null): Response;
    public function checkIban(IbanInput $input, ?Token $token = null): Response;

}
