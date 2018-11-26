<?php

declare(strict_types=1);

namespace Acme\Academic\UseCase\RegisterAcademic;

use Acme\Academic\Academic;
use Acme\Academic\Event\AcademicWasCreatedEvent;
use Acme\Academic\Repository\AcademicRepository;
use Acme\Academic\Repository\Exception\ImpossibleToSaveAcademic;
use Acme\Article\ArticleCollection;
use Acme\Common\EventHandler\EventDispatcher;

final class RegisterAcademicHandler
{
    /**
     * @var AcademicRepository
     */
    private $academicRepository;
    /**
     * @var EventDispatcher
     */
    private $dispatcher;

    public function __construct(AcademicRepository $academicRepository, EventDispatcher $dispatcher)
    {
        $this->academicRepository = $academicRepository;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @throws ImpossibleToSaveAcademic
     */
    public function __invoke(RegisterAcademicCommand $command): Academic
    {
        $academic = new Academic(
            $this->academicRepository->nextRegistrationNumber(),
            $command->getFirstName(),
            $command->getLastName(),
            $command->getEmail(),
            $command->getPassword(),
            $command->getMajor(),
            $command->getBirthDate(),
            new ArticleCollection()
        );

        $this->academicRepository->add($academic);

        ($this->dispatcher)(new AcademicWasCreatedEvent($academic));

        return $academic;
    }
}
