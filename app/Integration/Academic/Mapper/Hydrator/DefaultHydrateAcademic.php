<?php

declare(strict_types=1);

namespace App\Integration\Academic\Mapper\Hydrator;

use Acme\Academic\Academic;
use Acme\Academic\ValueObject\AcademicRegistrationNumber;
use Acme\Academic\ValueObject\BirthDate;
use Acme\Academic\ValueObject\Email;
use Acme\Academic\ValueObject\FirstName;
use Acme\Academic\ValueObject\LastName;
use Acme\Academic\ValueObject\Major;
use Acme\Academic\ValueObject\Password;
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
            new FirstName($rawAcademic['first_name']),
            new LastName($rawAcademic['last_name']),
            new Email($rawAcademic['email']),
            Password::fromHashedPassword($rawAcademic['password']),
            new Major($rawAcademic['major']),
            BirthDate::fromString($rawAcademic['birth_date']),
            new ArticleCollection(...$articles)
        );
    }
}
