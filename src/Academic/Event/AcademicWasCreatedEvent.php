<?php

declare(strict_types=1);

namespace Acme\Academic\Event;

use Acme\Academic\Academic;

final class AcademicWasCreatedEvent
{
    /**
     * @var Academic
     */
    private $academic;

    public function __construct(Academic $academic)
    {
        $this->academic = $academic;
    }

    public function academic(): Academic
    {
        return $this->academic;
    }
}
