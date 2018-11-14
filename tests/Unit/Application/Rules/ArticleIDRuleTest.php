<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Rules;

use App\Rules\ArticleIDRule;
use Faker\Generator as Faker;
use Tests\TestCase;

/**
 * @covers \App\Rules\ArticleIDRule
 */
class ArticleIDRuleTest extends TestCase
{
    /**
     * @test
     * @dataProvider validAttributeAndValueDataProvider
     */
    public function should_return_true(string $attribute, string $value): void
    {
        $rule = new ArticleIDRule();
        $this->assertTrue($rule->passes($attribute, $value));
    }

    /**
     * @test
     * @dataProvider invalidAttributeAndValueDataProvider
     */
    public function should_return_false_and_set_the_message(string $attribute, string $value): void
    {
        $rule = new ArticleIDRule();
        $this->assertEmpty($rule->message());
        $this->assertFalse($rule->passes($attribute, $value));
        $this->assertNotEmpty($rule->message());
    }

    public function validAttributeAndValueDataProvider(): array
    {
        /** @var Faker $faker */
        $faker = $this->factoryFaker->instance(Faker::class);

        return [
            [$faker->word, $faker->uuid],
        ];
    }

    public function invalidAttributeAndValueDataProvider(): array
    {
        /** @var Faker $faker */
        $faker = $this->factoryFaker->instance(Faker::class);

        return [
            [$faker->word, $faker->word],
        ];
    }
}
