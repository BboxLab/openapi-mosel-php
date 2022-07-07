<?php

declare(strict_types=1);


namespace Bboxlab\Mosel\Response;

use Bboxlab\Mosel\Authentication\Token\Token;

class Response
{
    public function __construct(private Token $token, private $content) {}

    /**
     * @return Token
     */
    public function getToken(): Token
    {
        return $this->token;
    }

    /**
     * @param Token $token
     */
    public function setToken(Token $token): void
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
