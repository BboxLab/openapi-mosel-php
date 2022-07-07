<?php

declare(strict_types=1);


namespace Bboxlab\Mosel\Email;

class EmailOutput
{
    private bool $contactEmailAddress;
    private bool $validEmailAddress;

    /**
     * @return bool
     */
    public function isContactEmailAddress(): bool
    {
        return $this->contactEmailAddress;
    }

    /**
     * @param bool $contactEmailAddress
     */
    public function setContactEmailAddress(bool $contactEmailAddress): void
    {
        $this->contactEmailAddress = $contactEmailAddress;
    }

    /**
     * @return bool
     */
    public function isValidEmailAddress(): bool
    {
        return $this->validEmailAddress;
    }

    /**
     * @param bool $validEmailAddress
     */
    public function setValidEmailAddress(bool $validEmailAddress): void
    {
        $this->validEmailAddress = $validEmailAddress;
    }

}
