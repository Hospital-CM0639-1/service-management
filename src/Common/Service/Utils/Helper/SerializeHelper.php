<?php

namespace App\Common\Service\Utils\Helper;

use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;
use Symfony\Component\Serializer\SerializerInterface;

readonly class SerializeHelper
{
    public function __construct(
        private SerializerInterface $serializer,
    ) {}

    public function serialize(mixed $data, array $groups = [], string $format = 'json'): string
    {
        return $this->serializer->serialize(
            data: $data,
            format: $format,
            context: (new ObjectNormalizerContextBuilder())
                ->withGroups($groups)
                ->toArray()
        );
    }
}