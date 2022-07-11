<?php

declare(strict_types=1);


namespace Bboxlab\Mosel\Iban;


use Bboxlab\Mosel\Dto\BtInputInterface;
use Symfony\Component\Validator\Constraints as Assert;

class IbanInput implements BtInputInterface
{
    /**
     * @Assert\Iban()
     * @var string $iban
     */
    private $iban;

    /**
     * @return string
     */
    public function getIban(): string
    {
        return $this->iban;
    }

    /**
     * @param string $iban
     */
    public function setIban(string $iban): void
    {
        $this->iban = $iban;
    }

}
