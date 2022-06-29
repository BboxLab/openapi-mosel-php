<?php

declare(strict_types=1);


namespace Bboxlab\Moselle\Authentication\Token;

class TokenVoter
{

    /** expirationTime is in sec, createdAt is a date in string with format 'Y-m-d H:i:s p', for example: format 2022-06-29 13:08:43 Z */
    public function checkTokenExpirationTime(int $expirationTime, string $createdAt): bool
    {
        $createAtDatetime = new \DateTime($createdAt);

        if ($createAtDatetime->getTimestamp() + $expirationTime < (new \DateTime())->getTimestamp()) {
            return true;
        }

        return false;
    }

    public function vote(BtToken $token): bool
    {
        if(!$this->checkTokenExpirationTime($token->getExpiresIn(), $token->getCreatedAt())) {
            return false;
        }

        return true;
    }
}
