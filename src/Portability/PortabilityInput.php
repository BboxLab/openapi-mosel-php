<?php

declare(strict_types=1);


namespace Bboxlab\Moselle\Portability;

use Bboxlab\Moselle\Dto\BtInputInterface;
use Symfony\Component\Validator\Constraints as Assert;

class PortabilityInput implements BtInputInterface
{
    /**
     * @Assert\Length(
     *      min = 12,
     *      max = 15,
     *      minMessage = "Phone number must be at least {{ limit }} characters long",
     *      maxMessage = "Phone number name cannot be longer than {{ limit }} characters"
     * )
     */
    private string $phoneNumber;

    /**
     * @Assert\Length(
     *      min = 4,
     *      max = 12,
     *      minMessage = "Rio code must be at least {{ limit }} characters long",
     *      maxMessage = "Rio code cannot be longer than {{ limit }} characters"
     * )
     */
    private string $rioCode;

    /**
     * @return string
     */
    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }

    /**
     * @param string $phoneNumber
     */
    public function setPhoneNumber(string $phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }

    /**
     * @return string
     */
    public function getRioCode(): string
    {
        return $this->rioCode;
    }

    /**
     * @param string $rioCode
     */
    public function setRioCode(string $rioCode): void
    {
        $this->rioCode = $rioCode;
    }

}

