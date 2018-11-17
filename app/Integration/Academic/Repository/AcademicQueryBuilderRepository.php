<?php

declare(strict_types=1);

namespace App\Integration\Academic\Repository;

use Acme\Academic\Academic;
use Acme\Academic\AcademicCollection;
use Acme\Academic\Repository\AcademicRepository;
use Acme\Academic\Repository\Exception\AcademicNotFound;
use Acme\Academic\Repository\Exception\ImpossibleToRetrieveAcademics;
use Acme\Academic\Repository\Exception\ImpossibleToSaveAcademic;
use Acme\Academic\ValueObject\AcademicRegistrationNumber;
use Acme\Common\Query\Pagination;
use App\Integration\Academic\Mapper\AcademicMapper;
use App\Integration\Common\Query\CrudFacade;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\QueryException;
use Psr\Log\LoggerInterface;

final class AcademicQueryBuilderRepository implements AcademicRepository
{
    public const TABLE_NAME = 'academics';

    private const SEQUENCE_ACADEMIC_ID = 'sequence_academic_id';
    /**
     * @var AcademicMapper
     */
    private $academicMapper;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var CrudFacade
     */
    private $query;
    /**
     * @var DatabaseManager
     */
    private $databaseManager;

    public function __construct(
        DatabaseManager $databaseManager,
        CrudFacade $query,
        AcademicMapper $academicMapper,
        LoggerInterface $logger
    ) {
        $this->academicMapper = $academicMapper;
        $this->logger = $logger;
        $this->query = $query;
        $this->databaseManager = $databaseManager;
    }

    public function getById(AcademicRegistrationNumber $academicID): Academic
    {
        try {
            $rawAcademic = $this->query->getById($academicID);
        } catch (QueryException $e) {
            $this->logger->error('database failure', ['exception' => $e, 'academic_id' => (string) $academicID]);
            throw new ImpossibleToRetrieveAcademics($e);
        }

        if (null === $rawAcademic) {
            $this->logger->warning('academic not found', ['academic_id' => (string) $academicID]);
            throw new AcademicNotFound($academicID);
        }

        return $this->academicMapper->fromArray($rawAcademic);
    }

    public function list(int $skip = self::DEFAULT_SKIP, int $take = self::DEFAULT_TAKE): AcademicCollection
    {
        try {
            $pagination = $this->getPagination($skip, $take);

            $rawAcademics = $this->query->getAll($pagination);
        } catch (QueryException $e) {
            $this->logger->warning('database failure', ['exception' => $e]);
            throw new ImpossibleToRetrieveAcademics($e);
        }

        return $this->serializeList($rawAcademics);
    }

    public function nextID(): AcademicRegistrationNumber
    {
        $nextNumber = $this->databaseManager->table(self::SEQUENCE_ACADEMIC_ID)->increment('id');

        return AcademicRegistrationNumber::fromInteger($nextNumber);
    }

    /**
     * @throws ImpossibleToSaveAcademic
     */
    public function add(Academic $academic): void
    {
        try {
            $rawAcademic = $this->academicMapper->fromAcademic($academic);
            $this->query->save($rawAcademic);
        } catch (QueryException $e) {
            $this->logger->error('database failure', ['exception' => $e, 'academic' => $rawAcademic]);
            throw new ImpossibleToSaveAcademic($e);
        }
    }

    /**
     * @throws ImpossibleToSaveAcademic
     */
    public function update(Academic $academic): void
    {
        try {
            $rawAcademic = $this->academicMapper->fromAcademic($academic);
            $this->query->update($academic->registrationNumber(), $rawAcademic);
        } catch (QueryException $e) {
            $this->logger->error('database failure', ['exception' => $e, 'academic' => $rawAcademic]);
            throw new ImpossibleToSaveAcademic($e);
        }
    }

    /**
     * @param int $skip
     * @param int $take
     *
     * @return Pagination
     */
    private function getPagination(int $skip, int $take): Pagination
    {
        if ($take > AcademicRepository::MAX_SIZE) {
            $take = AcademicRepository::MAX_SIZE;
        }

        return new Pagination($skip, $take);
    }

    private function serialize(array $academic): Academic
    {
        return $this->academicMapper->fromArray($academic);
    }

    /**
     * @param $rawAcademics
     *
     * @return array
     */
    private function serializeList(array $rawAcademics): AcademicCollection
    {
        $list = \array_map(
            function (array &$rawAcademic) {
                return $this->serialize((array) $rawAcademic);
            },
            $rawAcademics
        );

        return new AcademicCollection(...$list);
    }
}
