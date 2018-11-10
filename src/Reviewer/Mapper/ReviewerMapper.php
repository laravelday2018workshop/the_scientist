<?php

declare(strict_types=1);

namespace Acme\Reviewer\Mapper;

use Acme\Reviewer\Reviewer;

interface ReviewerMapper
{
    public function fromArray(array $rawReviewer): Reviewer;

    public function fromReviewer(Reviewer $reviewer): array;
}
