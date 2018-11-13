<?php

declare(strict_types=1);

namespace Acme\Academic;

use ArrayObject;
use IteratorIterator;

final class AcademicCollection extends IteratorIterator
{
    /**
     * @var Academic[]
     */
    private $academics;

    public function __construct(Academic ...$academics)
    {
        $this->academics = $academics;
        parent::__construct(new ArrayObject($this->academics));
    }

    /**
     * @return Academic[]
     */
    public function toArray(): array
    {
        return $this->academics;
    }
}