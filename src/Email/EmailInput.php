<?php

namespace Bboxlab\Moselle\Email;

use Bboxlab\Moselle\Dto\BtInputInterface;
use Symfony\Component\Validator\Constraints as Assert;

class EmailInput implements BtInputInterface
{
    /**
     * @Assert\Email()
     */
    private string $emailAddress;

    /**
     * @return string
     */
    public function getEmailAddress(): string
    {
        return $this->emailAddress;
    }

    /**
     * @param string $emailAddress
     */
    public function setEmailAddress(string $emailAddress): void
    {
        $this->emailAddress = $emailAddress;
    }

}
