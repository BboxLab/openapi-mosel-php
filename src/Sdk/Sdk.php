<?php

declare(strict_types=1);

namespace Bboxlab\Mosel\Sdk;

use Bboxlab\Mosel\Authentication\Credentials\Credentials;
use Bboxlab\Mosel\Authentication\Token\Token;
use Bboxlab\Mosel\Client\MoselClient;
use Bboxlab\Mosel\Configuration\Configuration;
use Bboxlab\Mosel\Configuration\ConfigurationInterface;
use Bboxlab\Mosel\Dto\BtInputInterface;
use Bboxlab\Mosel\Email\EmailChecker;
use Bboxlab\Mosel\Email\EmailInput;
use Bboxlab\Mosel\Iban\IbanInput;
use Bboxlab\Mosel\Portability\PortabilityChecker;
use Bboxlab\Mosel\Portability\PortabilityInput;
use Bboxlab\Mosel\Response\Response;
use Bboxlab\Mosel\Validation\Validator;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class Sdk implements SdkInterface
{
    private ?Validator $validator;

    public function __construct(
        private Credentials $credentials,
        private ConfigurationInterface $configuration,
    ) {
        // validate secrets and configuration inputs
        $this->validator = new Validator();
        $this->validator->validate($this->credentials);
        $this->validator->validate($configuration);
    }

    public function getConfiguration(): ?Configuration
    {
        return $this->configuration;
    }

    public function getCredentials(): ?Credentials
    {
        return $this->credentials;
    }

    public function checkEmail(EmailInput $input, ?Token $token = null): Response
    {
        return (new EmailChecker($this->validator, new MoselClient()))($input, $this->configuration , $this->credentials, $token);
    }

    public function checkPortability(PortabilityInput $input, ?Token $token = null): Response
    {
        return (new PortabilityChecker($this->validator, new MoselClient()))($input, $this->configuration , $this->credentials, $token);
    }

    public function checkIban(IbanInput $input, ?Token $token = null): Response
    {
        return (new PortabilityChecker($this->validator, new MoselClient()))($input, $this->configuration , $this->credentials, $token);
    }

}
