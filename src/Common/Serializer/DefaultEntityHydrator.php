<?php

declare(strict_types=1);

namespace Acme\Common\Serializer;

use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Serializer;

/**
 * Class DefaultEntityHydrator.
 */
class DefaultEntityHydrator implements EntityHydrator
{
    /**
     * @var Serializer
     */
    private $denormalizer;

    /**
     * ModelHydratorDefault constructor.
     *
     * @param DenormalizerInterface $denormalizer
     */
    public function __construct(DenormalizerInterface $denormalizer)
    {
        $this->denormalizer = $denormalizer;
    }

    /**
     * @param string $class
     * @param array  $data
     *
     * @return object
     */
    public function hydrate(string $class, array $data)
    {
        return $this->denormalizer->denormalize($data, $class);
    }
}
