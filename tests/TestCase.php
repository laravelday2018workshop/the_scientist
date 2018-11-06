<?php

declare(strict_types=1);

namespace Tests;

use Acme\Academic\Academic;
use Acme\Academic\ValueObject\AcademicID;
use Acme\Article\Article;
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
                $factoryMuffin->instance(AcademicID::class),
                $factoryMuffin->instance(ReviewerID::class),
                new DateTimeImmutable(),
                new DateTimeImmutable(),
                new DateTimeImmutable()
            );
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
            return new Academic($factoryMuffin->instance(AcademicID::class));
        });

        $factoryMuffin->define(AcademicID::class)->setMaker(function () use ($faker): AcademicID {
            return AcademicID::fromUUID($faker->uuid);
        });

        $factoryMuffin->define(Reviewer::class)->setMaker(function () use ($factoryMuffin): ReviewerID {
            return new Reviewer($factoryMuffin->instance(ReviewerID::class));
        });

        $factoryMuffin->define(ReviewerID::class)->setMaker(function () use ($faker): ReviewerID {
            return ReviewerID::fromUUID($faker->uuid);
        });

        return $factoryMuffin;
    }
}
