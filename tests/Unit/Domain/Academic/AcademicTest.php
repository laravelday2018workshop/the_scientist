<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: christian
 * Date: 11/14/18
 * Time: 11:48 AM.
 */

namespace Tests\Unit\Domain\Academic;

use Acme\Academic\Academic;
use Acme\Academic\ValueObject\AcademicRegistrationNumber;
use Acme\Academic\ValueObject\BirthDate;
use Acme\Academic\ValueObject\Email;
use Acme\Academic\ValueObject\FirstName;
use Acme\Academic\ValueObject\LastName;
use Acme\Academic\ValueObject\Major;
use Acme\Academic\ValueObject\Password;
use Acme\Article\ArticleCollection;
use Tests\TestCase;

/**
 * @covers \Acme\Academic\Academic
 */
class AcademicTest extends TestCase
{
    /**
     * @test
     */
    public function should_create_an_academic()
    {
        /** @var AcademicRegistrationNumber $academicId */
        $academicId = $this->factoryFaker->instance(AcademicRegistrationNumber::class);

        /** @var FirstName $firstName */
        $firstName = $this->factoryFaker->instance(FirstName::class);

        /** @var LastName $lastName */
        $lastName = $this->factoryFaker->instance(LastName::class);

        /** @var Email $email */
        $email = $this->factoryFaker->instance(Email::class);

        /** @var Password $password */
        $password = $this->factoryFaker->instance(Password::class);

        /** @var Major $major */
        $major = $this->factoryFaker->instance(Major::class);

        /** @var BirthDate $birthDate */
        $birthDate = $this->factoryFaker->instance(BirthDate::class);

        /** @var ArticleCollection $articles */
        $articles = $this->factoryFaker->instance(ArticleCollection::class);
        $academic = new Academic(
            $academicId,
            $firstName,
            $lastName,
            $email,
            $password,
            $major,
            $birthDate,
            $articles
        );

        $this->assertSame($academicId, $academic->registrationNumber());
        $this->assertSame($firstName, $academic->firstName());
        $this->assertSame($lastName, $academic->lastName());
        $this->assertSame($email, $academic->email());
        $this->assertSame($password, $academic->password());
        $this->assertSame($major, $academic->major());
        $this->assertSame($birthDate, $academic->birthDate());
        $this->assertSame($articles, $academic->articles());
    }
}
