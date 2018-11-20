<?php

declare(strict_types=1);

namespace App\Integration\Academic\Mapper\FromArray;

use Acme\Academic\Academic;
use Acme\Academic\ValueObject\AcademicRegistrationNumber;
use Acme\Article\ArticleCollection;
use App\Integration\Article\Mapper\Hydrator\HydrateArticle;

final class DefaultHydrateAcademic implements HydrateAcademic
{
    /**
     * @var HydrateArticle
     */
    private $fromArrayMapper;

    public function __construct(HydrateArticle $fromArrayMapper)
    {
        $this->fromArrayMapper = $fromArrayMapper;
    }

    public function __invoke(array $rawAcademic): Academic
    {
        $articles = \array_map($this->fromArrayMapper, $rawAcademic['articles']);

        return new Academic(
            AcademicRegistrationNumber::fromString($rawAcademic['id']),
            new ArticleCollection(...$articles)
        );
    }
}
