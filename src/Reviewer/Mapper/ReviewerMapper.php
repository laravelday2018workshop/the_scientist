<?php

declare(strict_types=1);

namespace Acme\Reviewer\Mapper;

use Acme\Reviewer\ReviewerID;

interface ReviewerMapper
{
    public function fromArray(array $rawReviewer): ReviewerID;

    public function fromReviewer(ReviewerID $reviewer): array;
}
