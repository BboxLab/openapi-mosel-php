<?php

declare(strict_types=1);


namespace Bboxlab\Moselle\Response;

use Bboxlab\Moselle\Authentication\Token\BtToken;

class Response
{
    public function __construct(private BtToken $token, private $content) {}

    /**
     * @return BtToken
     */
    public function getToken(): BtToken
    {
        return $this->token;
    }

    /**
     * @param BtToken $token
     */
    public function setToken(BtToken $token): void
    {
        $this->token = $token;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content): void
    {
        $this->content = $content;
    }

}
