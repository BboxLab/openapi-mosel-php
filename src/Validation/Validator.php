<?php

declare(strict_types=1);

namespace Bboxlab\Moselle\Validation;

use Bboxlab\Moselle\Exception\BtHttpBadRequestException;
use Symfony\Component\Validator\Validation;

class Validator
{
    public function checkSimpleValidation($input, $rules)
    {
        $validator = Validation::createValidator();
        $violations = $validator->validate($input, $rules);

        if (0 !== count($violations)) {
            $errorsString = (string) $violations;
            throw new BtHttpBadRequestException($errorsString);
        }
    }

    public function checkObjectValidation($objectToValidate)
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
