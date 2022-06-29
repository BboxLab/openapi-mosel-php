<?php

namespace Bboxlab\Moselle\Authentication\Token;

interface TokenInterface
{
    public function getAccessToken(): string;
    public function setAccessToken(string $accessToken): void;
    public function getExpiresIn(): int;
    public function setExpiresIn(int $expiresIn): void;
    public function getCreatedAt(): string;
}
