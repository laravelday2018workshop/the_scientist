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
use Acme\Article\ValueObject\ArticleID;
use App\Integration\Academic\Mapper\FromArray\HydrateAcademic;
use App\Integration\Academic\Mapper\Serializer\SerializeAcademic;
use App\Integration\Article\Repository\ArticleQueryBuilderRepository;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\QueryException;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;
use stdClass;

final class AcademicQueryBuilderRepository implements AcademicRepository
{
    public const TABLE_NAME = 'academics';

    private const SEQUENCE_ACADEMIC_ID = 'sequence_academic_id';

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var DatabaseManager
     */
    private $databaseManager;

    /**
     * @var SerializeAcademic
     */
    private $serializeAcademic;

    /**
     * @var HydrateAcademic
     */
    private $hydrateAcademic;

    public function __construct(
        DatabaseManager $databaseManager,
        SerializeAcademic $fromAcademicMapper,
        HydrateAcademic $fromArrayMapper,
        LoggerInterface $logger
    ) {
        $this->logger = $logger;
        $this->databaseManager = $databaseManager;
        $this->serializeAcademic = $fromAcademicMapper;
        $this->hydrateAcademic = $fromArrayMapper;
    }

    public function getById(AcademicRegistrationNumber $registrationNumber): Academic
    {
        try {
            $rawAcademic = (array) $this->databaseManager->table(self::TABLE_NAME)
                ->select()
                ->where('id', '=', (string) $registrationNumber)
                ->first();

            $rawArticles = $this->databaseManager->table(ArticleQueryBuilderRepository::TABLE_NAME)
                ->select()
                ->where('academic_id', '=', (string) $registrationNumber)
                ->limit(5)
                ->get()
                ->toArray();
        } catch (QueryException $e) {
            $this->logger->error('database failure', ['exception' => $e, 'academic_id' => (string) $registrationNumber]);
            throw new ImpossibleToRetrieveAcademics($e);
        }

        if ([] === $rawAcademic) {
            $this->logger->warning('academic not found', ['academic_id' => (string) $registrationNumber]);
            throw new AcademicNotFound($registrationNumber);
        }

        $rawArticles = \array_map(function (stdClass $rawArticle) {
            return (array) $rawArticle;
        }, $rawArticles);

        $rawAcademic['articles'] = $rawArticles;

        return ($this->hydrateAcademic)($rawAcademic);
    }

    public function list(int $skip = self::DEFAULT_SKIP, int $take = self::DEFAULT_TAKE): AcademicCollection
    {
        try {
            if ($take > AcademicRepository::MAX_SIZE) {
                $take = AcademicRepository::MAX_SIZE;
            }

            $rawAcademics = $this->databaseManager->table(self::TABLE_NAME)
                ->select()
                ->skip($skip)->take($take)
                ->get()->toArray();
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

    public function nextArticleID(): ArticleID
    {
        return ArticleID::fromUUID(Uuid::uuid4()->toString());
    }

    /**
     * @throws ImpossibleToSaveAcademic
     */
    public function add(Academic $academic): void
    {
        $rawAcademic = ($this->serializeAcademic)($academic);
        $rawArticles = $rawAcademic['articles'];
        unset($rawAcademic['articles']);

        try {
            $this->databaseManager->beginTransaction();
            foreach ($rawArticles as $rawArticle) {
                $this->databaseManager->table(ArticleQueryBuilderRepository::TABLE_NAME)->insert($rawArticle);
            }
            $this->databaseManager->table(self::TABLE_NAME)->insert($rawAcademic);
            $this->databaseManager->commit();
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
        $rawAcademic = ($this->serializeAcademic)($academic);
        $rawArticles = $rawAcademic['articles'];
        $rawAcademicWithoutArticles = $rawAcademic;
        unset($rawAcademicWithoutArticles['articles']);
        try {
            $this->databaseManager->beginTransaction();
            foreach ($rawArticles as $rawArticle) {
                $this->databaseManager->table(ArticleQueryBuilderRepository::TABLE_NAME)->updateOrInsert(['id' => $rawArticle['id']], $rawArticle);
            }
            $this->databaseManager->table(self::TABLE_NAME)->where('id', '=', (string) $academic->registrationNumber())->update($rawAcademicWithoutArticles);
            $this->databaseManager->commit();
        } catch (QueryException $e) {
            $this->logger->error('database failure', ['exception' => $e, 'academic' => $rawAcademic]);
            throw new ImpossibleToSaveAcademic($e);
        }
    }

    /**
     * @param $rawAcademics
     *
     * @return AcademicCollection
     */
    private function serializeList(array $rawAcademics): AcademicCollection
    {
        $list = \array_map(function (stdClass $rawAcademic) {
            return ($this->hydrateAcademic)((array) $rawAcademic);
        }, $rawAcademics);

        return new AcademicCollection(...$list);
    }
}
