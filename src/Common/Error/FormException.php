<?php

namespace App\Common\Error;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class FormException extends GenericException
{
    public function __construct(FormInterface $form, int $httpStatusCode = Response::HTTP_BAD_REQUEST, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct(
            message: $form->getErrors(true)->current()->getMessage(),
            httpStatusCode: $httpStatusCode,
            code: $code,
            previous: $previous
        );
    }

}