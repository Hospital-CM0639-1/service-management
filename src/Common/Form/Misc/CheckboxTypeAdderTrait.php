<?php

namespace App\Common\Form\Misc;

use App\Common\Enum\Error\CommonErrorCodeEnum;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

trait CheckboxTypeAdderTrait
{
    /**
     * Add a CheckboxType field to the form, converting the value to true, false or null,
     * based on the input
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     * @param string               $fieldName the name of the field
     * @param array                $fieldOptions options for the field
     * @param mixed                $trueValue the value to consider as true
     * @param mixed                $falseValue the value to consider as false
     * @param mixed|null           $nullValue the value to consider as null
     * @param string               $errorMessage
     * @return FormBuilderInterface the form builder
     */
    protected function addCheckboxField(
        FormBuilderInterface $builder,
        array $options,
        string $fieldName,
        array $fieldOptions = [],
        mixed $trueValue = true,
        mixed $falseValue = false,
        mixed $nullValue = null,
        string $errorMessage = CommonErrorCodeEnum::DEFAULT_400,
    ): FormBuilderInterface
    {
        return $builder
            ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($fieldName, $fieldOptions, $trueValue, $falseValue, $nullValue, $errorMessage) {
                $form = $event->getForm();
                $data = $event->getData();

                $fieldValue = $data[$fieldName] ?? $nullValue;
                if ($fieldValue !== $nullValue) {
                    dump($fieldValue);
                    $form
                        ->add($fieldName, CheckboxType::class, $fieldOptions);
                    if ($fieldValue === $trueValue) {
                        $data[$fieldName] = true;
                    } elseif ($fieldValue === $falseValue) {
                        $data[$fieldName] = false;
                    } else {
                        $form->addError(new FormError(message: $errorMessage));
                        return;
                    }

                    $event->setData($data);
                }
            });
    }
}