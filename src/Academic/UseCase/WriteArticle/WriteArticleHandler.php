<?php

declare(strict_types=1);

namespace Acme\Academic\UseCase\WriteArticle;

use Acme\Academic\Academic;
use Acme\Academic\Repository\AcademicRepository;
use Acme\Article\Article;

final class WriteArticleHandler
{
    /**
     * @var AcademicRepository
     */
    private $academicRepository;

    public function __construct(AcademicRepository $academicRepository)
    {
        $this->academicRepository = $academicRepository;
    }

    public function __invoke(WriteArticleCommand $command): Academic
    {
        $academic = $this->academicRepository->getById($command->getAcademicRegistrationNumber());

        $article = Article::create(
            $this->academicRepository->nextArticleID(),
            $command->getTitle(),
            $command->getBody(),
            $command->getAcademicRegistrationNumber(),
            new \DateTimeImmutable()
        );

        $academic->write($article);
        $this->academicRepository->update($academic);

        return $academic;
    }
}
