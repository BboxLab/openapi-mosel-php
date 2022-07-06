<?php

declare(strict_types=1);

namespace Bboxlab\Moselle\Sdk;

use Bboxlab\Moselle\Authentication\Credentials\Credentials;
use Bboxlab\Moselle\Authentication\Token\Token;
use Bboxlab\Moselle\Client\MoselleClient;
use Bboxlab\Moselle\Configuration\Configuration;
use Bboxlab\Moselle\Configuration\ConfigurationInterface;
use Bboxlab\Moselle\Dto\BtInputInterface;
use Bboxlab\Moselle\Email\EmailChecker;
use Bboxlab\Moselle\Email\EmailInput;
use Bboxlab\Moselle\Portability\PortabilityChecker;
use Bboxlab\Moselle\Portability\PortabilityInput;
use Bboxlab\Moselle\Response\Response;
use Bboxlab\Moselle\Validation\Validator;
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
        return (new EmailChecker($this->validator, new MoselleClient()))($input, $this->configuration , $this->credentials, $token);
    }

    public function checkPortability(PortabilityInput $input, ?Token $token = null): Response
    {
        return (new PortabilityChecker($this->validator, new MoselleClient()))($input, $this->configuration , $this->credentials, $token);
    }

}
