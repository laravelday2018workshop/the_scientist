<?php

declare(strict_types=1);

namespace App\Integration\Academic\Repository;

use Acme\Academic\Academic;
use Acme\Academic\AcademicCollection;
use Acme\Academic\Repository\AcademicRepository;
use Acme\Academic\Repository\Exception\AcademicNotFound;
use Acme\Academic\Repository\Exception\ImpossibleToRetrieveAcademics;
use Acme\Academic\Repository\Exception\ImpossibleToSaveAcademic;
use Acme\Academic\ValueObject\AcademicID;
use App\Integration\Academic\Mapper\AcademicMapper;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\QueryException;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;
use stdClass;

final class AcademicQueryBuilderRepository implements AcademicRepository
{
    private const TABLE_NAME = 'academics';

    /**
     * @var DatabaseManager
     */
    private $database;

    /**
     * @var AcademicMapper
     */
    private $academicMapper;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(DatabaseManager $database, AcademicMapper $academicMapper, LoggerInterface $logger)
    {
        $this->database = $database;
        $this->academicMapper = $academicMapper;
        $this->logger = $logger;
    }

    public function getById(AcademicID $academicID): Academic
    {
        try {
            $rawAcademic =
                $this->database->table(self::TABLE_NAME)
                    ->select()
                    ->where('id', '=', (string) $academicID)
                    ->first();
        } catch (QueryException $e) {
            $this->logger->error('database failure', ['exception' => $e, 'academic_id' => (string) $academicID]);
            throw new ImpossibleToRetrieveAcademics($e);
        }

        if (null === $rawAcademic) {
            $this->logger->warning('academic not found', ['academic_id' => (string) $academicID]);
            throw new AcademicNotFound($academicID);
        }

        return $this->academicMapper->fromArray((array) $rawAcademic);
    }

    public function list(int $skip = self::DEFAULT_SKIP, int $take = self::DEFAULT_TAKE): AcademicCollection
    {
        if ($take > AcademicRepository::MAX_SIZE) {
            $take = AcademicRepository::MAX_SIZE;
        }

        try {
            $rawAcademics = $this->database->table(self::TABLE_NAME)->select()->skip($skip)->take($take)->get();
        } catch (QueryException $e) {
            $this->logger->warning('database failure', ['exception' => $e]);
            throw new ImpossibleToRetrieveAcademics($e);
        }

        $academics = \array_map(function (stdClass $rawAcademic) {
            return $this->academicMapper->fromArray((array) $rawAcademic);
        }, $rawAcademics->toArray());

        return new AcademicCollection(...$academics);
    }

    public function nextID(): AcademicID
    {
        return AcademicID::fromUUID((string) Uuid::uuid4());
    }

    /**
     * @throws ImpossibleToSaveAcademic
     */
    public function add(Academic $academic): void
    {
        $rawAcademic = $this->academicMapper->fromAcademic($academic);

        try {
            $insert = $this->database->table(self::TABLE_NAME)->insert($rawAcademic);
        } catch (QueryException $e) {
            $this->logger->error('database failure', ['exception' => $e, 'academic' => $rawAcademic]);
            throw new ImpossibleToSaveAcademic($e);
        }

        if (false === $insert) {
            $this->logger->warning('impossible to add academic', ['academic' => $rawAcademic]);
            throw new ImpossibleToSaveAcademic();
        }
    }

    /**
     * @throws ImpossibleToSaveAcademic
     */
    public function update(Academic $academic): void
    {
        $rawAcademic = $this->academicMapper->fromAcademic($academic);

        try {
            $update = $this->database->table(self::TABLE_NAME)->update($rawAcademic);
        } catch (QueryException $e) {
            $this->logger->error('database failure', ['exception' => $e, 'academic' => $rawAcademic]);
            throw new ImpossibleToSaveAcademic($e);
        }

        if (0 === $update) {
            $this->logger->warning('impossible to update academic', ['academic' => $rawAcademic]);
            throw new ImpossibleToSaveAcademic();
        }
    }
}
