<?php

declare(strict_types=1);

namespace Tests\Feature;

use Acme\Academic\Academic;
use Acme\Academic\Repository\AcademicRepository;
use App\Integration\Academic\Repository\InMemoryAcademicRepository;
use Tests\TestCase;

/**
 * @covers \App\Http\Controllers\GetAcademicController
 * @covers \App\Http\Requests\GetAcademicRequest
 */
class GetAcademicTest extends TestCase
{
    /** @var AcademicRepository */
    private $repository;

    public function setUp(): void
    {
        parent::setUp();

        $this->repository = new InMemoryAcademicRepository();
        $this->app->instance(AcademicRepository::class, $this->repository);
    }

    /**
     * @test
     */
    public function should_get_an_academic(): void
    {
        /** @var Academic $academic */
        $academic = $this->factoryFaker->instance(Academic::class);

        $this->repository->add($academic);

        $response = $this->get("academics/{$academic->registrationNumber()}");

        $response->assertStatus(200);
        $response->assertJson(
            [
                'id' => (string) $academic->registrationNumber(),
                'first_name' => (string) $academic->firstName(),
                'last_name' => (string) $academic->lastName(),
                'email' => (string) $academic->email(),
                'major' => (string) $academic->major(),
                'birth_date' => (string) $academic->birthDate(),
                'articles' => [],
            ]
        );
    }

    /**
     * @test
     */
    public function should_not_found_an_academic(): void
    {
        /** @var Academic $academic */
        $academic = $this->factoryFaker->instance(Academic::class);
        $response = $this->get("academics/{$academic->registrationNumber()}");
        $response->assertStatus(404);
        $response->assertJson(['message' => "Academic with ID \"{$academic->registrationNumber()}\" was not found"]);
    }

    /**
     * @test
     */
    public function should_throw_invalid_uuid_exception(): void
    {
        $badUUID = 'bad-uuid';
        $response = $this->get("academics/$badUUID");

        $response->assertStatus(404);
        $response->assertJson(['id' => ["The given value is not valid. Given \"$badUUID\""]]);
    }
}
