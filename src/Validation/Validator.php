<?php

declare(strict_types=1);

namespace Bboxlab\Mosel\Validation;

use Bboxlab\Mosel\Exception\BtHttpBadRequestException;
use Symfony\Component\Validator\Validation;

class Validator
{
    public function validateWithRules($input, $rules)
    {
        $validator = Validation::createValidator();
        $violations = $validator->validate($input, $rules);

        if (0 !== count($violations)) {
            $errorsString = (string) $violations;
            throw new BtHttpBadRequestException($errorsString);
        }
    }

    public function validate($objectToValidate)
    {
        $validator = Validation::createValidatorBuilder()
            ->enableAnnotationMapping()
            ->addDefaultDoctrineAnnotationReader()
            ->getValidator()
        ;

        $violations = $validator->validate($objectToValidate);

        if (0 !== count($violations)) {
            $errorsString = (string) $violations;
            throw new BtHttpBadRequestException($errorsString);
        }
    }
}
