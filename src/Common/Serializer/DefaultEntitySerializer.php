<?php

declare(strict_types=1);

namespace Acme\Common\Serializer;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Class DefaultEntitySerializer.
 */
class DefaultEntitySerializer implements EntitySerializer
{
    /**
     * @var NormalizerInterface
     */
    private $normalizer;

    /**
     * DefaultEntitySerializer constructor.
     *
     * @param NormalizerInterface $normalizer
     */
    public function __construct(NormalizerInterface $normalizer)
    {
        $this->normalizer = $normalizer;
    }

    /**
     * @param object $entity
     *
     * @return array
     */
    public function serialize($entity): array
    {
        return $this->normalizer->normalize($entity);
    }
}
