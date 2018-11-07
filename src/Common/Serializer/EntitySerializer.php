<?php

declare(strict_types=1);

namespace Acme\Common\Serializer;

/**
 * Interface EntitySerializer.
 */
interface EntitySerializer
{
    /**
     * @param object $entity
     *
     * @return array
     */
    public function serialize($entity): array;
}
