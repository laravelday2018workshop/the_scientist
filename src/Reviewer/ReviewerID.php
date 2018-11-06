<?php

declare(strict_types=1);

namespace Acme\Reviewer;

use Acme\Reviewer\ValueObject\ReviewerID;

final class ReviewerID
{
    /**
     * @var ReviewerID
     */
    private $reviewerID;

    public function __construct(ReviewerID $reviewerID)
    {
        $this->reviewerID = $reviewerID;
    }

    public function id(): ReviewerID
    {
        return $this->reviewerID;
    }
}
