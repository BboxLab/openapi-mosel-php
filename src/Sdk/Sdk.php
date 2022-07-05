<?php

declare(strict_types=1);

namespace Bboxlab\Moselle\Sdk;

use Bboxlab\Moselle\Authentication\Credentials\Credentials;
use Bboxlab\Moselle\Authentication\Token\Token;
use Bboxlab\Moselle\Configuration\Configuration;
use Bboxlab\Moselle\Configuration\ConfigurationInterface;
use Bboxlab\Moselle\Email\EmailChecker;
use Bboxlab\Moselle\Response\Response;
use Bboxlab\Moselle\Validation\Validator;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class Sdk
{
    private ?ConfigurationInterface $configuration;
    private ?Credentials $credentials;
    private ?Validator $validator;

    public function __construct(
        string $clientId,
        string $secret,
        ConfigurationInterface $configuration,
    ) {
        // validate secrets input
        $this->validator = new Validator();
        $rules = [
            new Length(['max' => 500]),
            new NotBlank(),
        ];
        $this->validator->checkSimpleValidation($clientId, $rules);
        $this->validator->checkSimpleValidation($clientId, $rules);

        $credentials = new Credentials();
        $credentials->setUsername($clientId);
        $credentials->setPassword($secret);
        $this->credentials = $credentials;

        // validate $configuration input
        $this->validator->checkObjectValidation($configuration);
        $this->configuration = $configuration;
    }

    public function getConfiguration(): ?Configuration
    {
        return $this->configuration;
    }

    public function getCredentials(): ?Credentials
    {
        return $this->credentials;
    }

    public function checkEmail(string $email, ?Token $token = null): Response
    {
        return (new EmailChecker($this->validator))($email,$this->configuration , $this->credentials, $token);
    }
}
