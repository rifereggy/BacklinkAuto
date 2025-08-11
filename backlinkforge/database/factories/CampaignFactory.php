<?php

namespace Database\Factories;

use App\Models\Campaign;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Campaign>
 */
class CampaignFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Campaign::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'team_id' => Team::factory(),
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->paragraph(),
            'status' => $this->faker->randomElement(['draft', 'active', 'paused', 'completed']),
            'graph_json' => [
                'nodes' => [
                    [
                        'id' => 'web2.0-1',
                        'type' => 'web2.0',
                        'position' => ['x' => 100, 'y' => 100],
                        'data' => [
                            'name' => 'WordPress Blog',
                            'provider' => 'wordpress',
                            'url' => 'https://example.wordpress.com',
                        ]
                    ],
                    [
                        'id' => 'wiki-1',
                        'type' => 'wiki',
                        'position' => ['x' => 300, 'y' => 100],
                        'data' => [
                            'name' => 'MediaWiki Page',
                            'provider' => 'mediawiki',
                            'url' => 'https://example.wiki.com',
                        ]
                    ]
                ],
                'edges' => [
                    [
                        'id' => 'edge-1',
                        'source' => 'web2.0-1',
                        'target' => 'wiki-1',
                    ]
                ]
            ],
            'settings' => [
                'delay_between_posts' => $this->faker->numberBetween(30, 300),
                'max_retries' => $this->faker->numberBetween(1, 5),
                'use_proxies' => $this->faker->boolean(),
                'ai_content_generation' => $this->faker->boolean(),
            ],
            'started_at' => $this->faker->optional()->dateTimeBetween('-1 month', 'now'),
            'completed_at' => $this->faker->optional()->dateTimeBetween('-1 week', 'now'),
        ];
    }

    /**
     * Indicate that the campaign is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
            'started_at' => now(),
        ]);
    }

    /**
     * Indicate that the campaign is completed.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
            'started_at' => now()->subDays(7),
            'completed_at' => now(),
        ]);
    }

    /**
     * Indicate that the campaign is draft.
     */
    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'draft',
            'started_at' => null,
            'completed_at' => null,
        ]);
    }
}
