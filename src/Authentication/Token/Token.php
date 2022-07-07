<?php

declare(strict_types=1);


namespace Bboxlab\Mosel\Authentication\Token;


use Symfony\Component\Validator\Constraints as Assert;

class Token implements TokenInterface
{
    /**
     * @Assert\NotBlank
     */
    private string $accessToken;

    /**
     * @Assert\Positive
     */
    private int $expiresIn;
    private ?string $tokenType;
    private ?int $refreshCredit;
    private ?string $scope;
    private ?bool $new = false;

    /**
     * @Assert\DateTime(
     *     format="Y-m-d H:i:s p"
     * )
     */
    private string $createdAt;

    /**
     * @return string
     */
    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    /**
     * @param string $accessToken
     */
    public function setAccessToken(string $accessToken): void
    {
        $this->accessToken = $accessToken;
    }

    /**
     * @return int
     */
    public function getExpiresIn(): int
    {
        return $this->expiresIn;
    }

    /**
     * @param int $expiresIn
     */
    public function setExpiresIn(int $expiresIn): void
    {
        $this->expiresIn = $expiresIn;
    }

    /**
     * @return string|null
     */
    public function getTokenType(): ?string
    {
        return $this->tokenType;
    }

    /**
     * @param string|null $tokenType
     */
    public function setTokenType(?string $tokenType): void
    {
        $this->tokenType = $tokenType;
    }

    /**
     * @return int|null
     */
    public function getRefreshCredit(): ?int
    {
        return $this->refreshCredit;
    }

    /**
     * @param int|null $refreshCredit
     */
    public function setRefreshCredit(?int $refreshCredit): void
    {
        $this->refreshCredit = $refreshCredit;
    }

    /**
     * @return string|null
     */
    public function getScope(): ?string
    {
        return $this->scope;
    }

    /**
     * @param string|null $scope
     */
    public function setScope(?string $scope): void
    {
        $this->scope = $scope;
    }

    /**
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    /**
     * @param string $createdAt
     */
    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return bool|null
     */
    public function isNew(): ?bool
    {
        return $this->new;
    }

    /**
     * @param bool|null $new
     */
    public function setNew(?bool $new): void
    {
        $this->new = $new;
    }

}
