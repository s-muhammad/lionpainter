<?php

namespace Database\Factories;

use App\Enums\KeywordContentType;
use App\Enums\KeywordDifficulty;
use App\Enums\KeywordIntent;
use App\Enums\KeywordStatus;
use App\Models\SeoKeyword;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SeoKeyword>
 */
class SeoKeywordFactory extends Factory
{
    protected $model = SeoKeyword::class;

    public function definition(): array
    {
        return [
            'keyword' => fake()->unique()->words(3, true),
            'cluster' => fake()->randomElement(['Core', 'Heritage', 'Cost', 'Location', 'Guides']),
            'intent' => fake()->randomElement(KeywordIntent::cases()),
            'content_type' => fake()->randomElement(KeywordContentType::cases()),
            'target_url' => null,
            'priority' => fake()->numberBetween(1, 3),
            'status' => KeywordStatus::Idea,
            'difficulty' => fake()->randomElement(KeywordDifficulty::cases()),
            'search_volume' => fake()->numberBetween(10, 5000),
            'current_position' => null,
            'target_date' => null,
            'notes' => null,
        ];
    }

    public function published(): static
    {
        return $this->state(fn () => [
            'status' => KeywordStatus::Published,
            'target_url' => '/services/'.fake()->slug(2),
        ]);
    }

    public function planned(): static
    {
        return $this->state(fn () => [
            'status' => KeywordStatus::Planned,
            'target_date' => now()->addWeeks(2)->toDateString(),
        ]);
    }

    public function highPriority(): static
    {
        return $this->state(fn () => ['priority' => 1]);
    }
}
