<?php

declare(strict_types=1);


namespace Bboxlab\Mosel\Authentication\Credentials;

use Symfony\Component\Validator\Constraints as Assert;

class Credentials
{
    /**
     * @Assert\Length(
     *     max = 200
     * )
     */
    private string $username;

    /**
     * @Assert\Length(
     *     max = 200
     * )
     */
    private string $password;

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

}
