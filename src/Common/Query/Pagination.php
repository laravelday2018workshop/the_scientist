<?php

declare(strict_types=1);

namespace Acme\Common\Query;

final class Pagination
{
    /**
     * @var int
     */
    private $skip;

    /**
     * @var int
     */
    private $take;

    public function __construct(int $skip, int $take)
    {
        $this->setSkip($skip);
        $this->setTake($take);
    }

    /**
     * @return int
     */
    public function skip(): int
    {
        return $this->skip;
    }

    /**
     * @return int
     */
    public function take(): int
    {
        return $this->take;
    }

    /**
     * @param int $skip
     */
    private function setSkip(int $skip): void
    {
        $this->skip = ($skip < 0) ? 0 : $skip;
    }

    /**
     * @param int $take
     */
    private function setTake(int $take): void
    {
        $this->take = ($take < 1) ? 1 : $take;
    }
}
