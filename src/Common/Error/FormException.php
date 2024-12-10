<?php

namespace App\Common\Error;

use Symfony\Component\Form\FormInterface;
use Throwable;

class FormException extends GenericException
{
    public function __construct(FormInterface $form, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($form->getErrors(true)->current()->getMessage(), $code, $previous);
    }

}