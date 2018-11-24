<?php

declare(strict_types=1);

namespace Tests;

use Acme\Academic\Academic;
use Acme\Academic\ValueObject\AcademicRegistrationNumber;
use Acme\Academic\ValueObject\BirthDate;
use Acme\Academic\ValueObject\Email;
use Acme\Academic\ValueObject\FirstName;
use Acme\Academic\ValueObject\LastName;
use Acme\Academic\ValueObject\Major;
use Acme\Academic\ValueObject\Password;
use Acme\Article\Article;
use Acme\Article\ArticleCollection;
use Acme\Article\ValueObject\ArticleID;
use Acme\Article\ValueObject\Body;
use Acme\Article\ValueObject\Title;
use Acme\Reviewer\Reviewer;
use Acme\Reviewer\ValueObject\ReviewerID;
use DateTimeImmutable;
use Faker\Factory as FakerFactory;
use Faker\Generator as Faker;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use League\FactoryMuffin\FactoryMuffin;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * @var FactoryMuffin
     */
    protected $factoryFaker;

    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        $this->factoryFaker = $this->buildFactory();
        parent::__construct($name, $data, $dataName);
    }

    private function buildFactory(): FactoryMuffin
    {
        $faker = FakerFactory::create();
        $factoryMuffin = new FactoryMuffin();

        $factoryMuffin->define(Faker::class)->setMaker(function () use ($faker) {
            return $faker;
        });

        $factoryMuffin->define(Article::class)->setMaker(function () use ($factoryMuffin): Article {
            return new Article(
                $factoryMuffin->instance(ArticleID::class),
                $factoryMuffin->instance(Title::class),
                $factoryMuffin->instance(Body::class),
                $factoryMuffin->instance(AcademicRegistrationNumber::class),
                $factoryMuffin->instance(ReviewerID::class),
                new DateTimeImmutable(),
                new DateTimeImmutable(),
                new DateTimeImmutable()
            );
        });

        $factoryMuffin->define(ArticleCollection::class)->setMaker(function () use ($factoryMuffin): ArticleCollection {
            return new ArticleCollection(...\array_fill(0, 5, $factoryMuffin->instance(Article::class)));
        });

        $factoryMuffin->define(ArticleID::class)->setMaker(function () use ($faker): ArticleID {
            return ArticleID::fromUUID($faker->uuid);
        });

        $factoryMuffin->define(Title::class)->setMaker(function () use ($faker): Title {
            return new Title($faker->sentence(3, 10));
        });

        $factoryMuffin->define(Body::class)->setMaker(function () use ($faker): Body {
            return new Body($faker->sentence(3, 10));
        });

        $factoryMuffin->define(Academic::class)->setMaker(function () use ($factoryMuffin): Academic {
            return new Academic(
                $factoryMuffin->instance(AcademicRegistrationNumber::class),
                $factoryMuffin->instance(FirstName::class),
                $factoryMuffin->instance(LastName::class),
                $factoryMuffin->instance(Email::class),
                $factoryMuffin->instance(Password::class),
                $factoryMuffin->instance(Major::class),
                $factoryMuffin->instance(BirthDate::class),
                new ArticleCollection()
            );
        });

        $factoryMuffin->define(AcademicRegistrationNumber::class)->setMaker(function () use ($faker): AcademicRegistrationNumber {
            return AcademicRegistrationNumber::fromInteger($faker->numberBetween(1000000000, 9000000000));
        });

        $factoryMuffin->define(FirstName::class)->setMaker(function () use ($faker): FirstName {
            return new FirstName($faker->firstName);
        });

        $factoryMuffin->define(LastName::class)->setMaker(function () use ($faker): LastName {
            return new LastName($faker->lastName);
        });

        $factoryMuffin->define(Email::class)->setMaker(function () use ($faker): Email {
            return new Email($faker->firstName.Email::EMAIL_DOMAIN);
        });

        $factoryMuffin->define(Password::class)->setMaker(function () use ($faker): Password {
            return Password::fromClearPassword($faker->password);
        });

        $factoryMuffin->define(Major::class)->setMaker(function () use ($faker): Major {
            return Major::BIOLOGICAL_SCIENCE();
        });

        $factoryMuffin->define(BirthDate::class)->setMaker(function () use ($faker): BirthDate {
            return BirthDate::fromString($faker->dateTime('-25 years')->format(BirthDate::DATE_FORMAT));
        });

        $factoryMuffin->define(Reviewer::class)->setMaker(function () use ($factoryMuffin): Reviewer {
            return new Reviewer($factoryMuffin->instance(ReviewerID::class));
        });

        $factoryMuffin->define(ReviewerID::class)->setMaker(function () use ($faker): ReviewerID {
            return ReviewerID::fromUUID($faker->uuid);
        });

        return $factoryMuffin;
    }
}
