<?php

declare(strict_types=1);

namespace Acme\Common\Serializer;

/**
 * Interface EntityHydrator.
 */
interface EntityHydrator
{
    /**
     * @param string $className
     * @param array  $data
     *
     * @return object
     */
    public function hydrate(string $className, array $data);
}
