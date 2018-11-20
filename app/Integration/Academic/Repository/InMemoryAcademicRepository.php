<?php

declare(strict_types=1);

namespace App\Integration\Academic\Repository;

use Acme\Academic\Academic;
use Acme\Academic\AcademicCollection;
use Acme\Academic\Repository\AcademicRepository;
use Acme\Academic\Repository\Exception\AcademicNotFound;
use Acme\Academic\ValueObject\AcademicRegistrationNumber;
use Acme\Article\ValueObject\ArticleID;
use Illuminate\Support\Collection;
use Ramsey\Uuid\Uuid;

class InMemoryAcademicRepository implements AcademicRepository
{
    /** @var Collection<Academic> */
    private $academics;

    public function __construct()
    {
        $this->academics = new Collection();
    }

    public function getById(AcademicRegistrationNumber $academicID): Academic
    {
        if (!$this->academics->has((string) $academicID)) {
            throw new AcademicNotFound($academicID);
        }

        return $this->academics->get((string) $academicID);
    }

    public function list(int $skip = self::DEFAULT_SKIP, int $take = self::DEFAULT_TAKE): AcademicCollection
    {
        if ($take > AcademicRepository::MAX_SIZE) {
            $take = AcademicRepository::MAX_SIZE;
        }

        $academics = $this->academics->splice($skip)->take($take)->values();

        return new AcademicCollection(...$academics);
    }

    public function nextID(): AcademicRegistrationNumber
    {
        return AcademicRegistrationNumber::fromInteger(\random_int(1000000000, 9000000000));
    }

    public function add(Academic $academic): void
    {
        $this->academics->put((string) $academic->registrationNumber(), $academic);
    }

    public function update(Academic $academic): void
    {
        $this->academics->put((string) $academic->registrationNumber(), $academic);
    }

    public function nextArticleID(): ArticleID
    {
        return ArticleID::fromUUID((string) Uuid::uuid4());
    }
}
