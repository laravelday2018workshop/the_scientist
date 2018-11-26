<?php

declare(strict_types=1);

namespace Tests\Feature;

use Acme\Academic\Academic;
use Acme\Academic\Repository\AcademicRepository;
use Acme\Article\Repository\ArticleRepository;
use App\Integration\Academic\Repository\InMemoryAcademicRepository;
use Tests\TestCase;

/**
 * @covers \App\Http\Controllers\RegisterAcademicController
 * @covers \App\Http\Requests\RegisterAcademicRequest
 */
class RegisterAcademicTest extends TestCase
{
    /** @var ArticleRepository */
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
    public function should_register_an_academic(): void
    {
        $this->assertCount(0, $this->repository->list()->toArray());

        /** @var Academic $academic */
        $academic = $this->factoryFaker->instance(Academic::class);
        $response = $this->post('academics/', [
            'firstName' => (string) $academic->firstName(),
            'lastName' => (string) $academic->lastName(),
            'email' => (string) $academic->email(),
            'password' => (string) $academic->password(),
            'major' => (string) $academic->major(),
            'birthDate' => (string) $academic->birthDate(),
        ]);

        $articleCollection = $this->repository->list()->toArray();
        $this->assertCount(1, $articleCollection);
        /** @var Academic $storedAcademic */
        $storedAcademic = \array_shift($articleCollection);
        $response->assertStatus(201);
        $response->assertJson(
            [
                'id' => (string) $storedAcademic->registrationNumber(),
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
    public function should_fail_due_to_empty_body(): void
    {
        $response = $this->post('academics/');
        $response->assertStatus(422);
        $response->assertJson([
            'firstName' => ['The first name field is required.'],
            'lastName' => ['The last name field is required.'],
            'email' => ['The email field is required.'],
            'password' => ['The password field is required.'],
            'birthDate' => ['The birth date field is required.'],
            'major' => ['The major field is required.'],
        ]);
    }

    /**
     * @test
     */
    public function should_fail_due_to_invalid_values(): void
    {
        $response = $this->post('academics/', [
            'firstName' => 'a',
            'lastName' => 'b',
            'email' => 'dpe@hotmcail.com',
            'password' => 'pwd',
            'birthDate' => '10/10/1990',
            'major' => 'computer science',
        ]);
        $response->assertStatus(422);
        $response->assertJson([
            'firstName' => ['The given value "a" is not valid to create a First name. Min length is 2 and max length is 100'],
            'lastName' => ['The given value "b" is not valid to create a Last name. Min length is 3 and max length is 100'],
            'email' => ['The given value "dpe@hotmcail.com" is not valid to create an Email. It must end with @the.com'],
            'password' => ['The given value value is not valid to create a Password, it must be length at least 6 chars'],
            'birthDate' => ['The birth date is invalid or in the future, expected format Y-m-d. Given: 10/10/1990 years'],
            'major' => ['The given value computer science is not a valid major'],
        ]);
    }
}
