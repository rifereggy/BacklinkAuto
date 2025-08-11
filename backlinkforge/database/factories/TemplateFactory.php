<?php

namespace Database\Factories;

use App\Models\Template;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Template>
 */
class TemplateFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Template::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $templateTypes = [
            'web2.0_pyramid' => [
                'name' => 'Web2.0 Pyramid',
                'description' => 'Classic web2.0 pyramid structure for link building',
                'graph_json' => [
                    'nodes' => [
                        ['id' => 'web2.0-1', 'type' => 'web2.0', 'position' => ['x' => 100, 'y' => 100]],
                        ['id' => 'web2.0-2', 'type' => 'web2.0', 'position' => ['x' => 300, 'y' => 100]],
                        ['id' => 'wiki-1', 'type' => 'wiki', 'position' => ['x' => 200, 'y' => 250]],
                    ],
                    'edges' => [
                        ['source' => 'web2.0-1', 'target' => 'wiki-1'],
                        ['source' => 'web2.0-2', 'target' => 'wiki-1'],
                    ]
                ]
            ],
            'forum_network' => [
                'name' => 'Forum Network',
                'description' => 'Forum-based link building network',
                'graph_json' => [
                    'nodes' => [
                        ['id' => 'forum-1', 'type' => 'forum', 'position' => ['x' => 100, 'y' => 100]],
                        ['id' => 'forum-2', 'type' => 'forum', 'position' => ['x' => 300, 'y' => 100]],
                        ['id' => 'profile-1', 'type' => 'profile', 'position' => ['x' => 200, 'y' => 250]],
                    ],
                    'edges' => [
                        ['source' => 'forum-1', 'target' => 'profile-1'],
                        ['source' => 'forum-2', 'target' => 'profile-1'],
                    ]
                ]
            ],
            'mixed_tier' => [
                'name' => 'Mixed Tier Structure',
                'description' => 'Mixed tier structure with various platforms',
                'graph_json' => [
                    'nodes' => [
                        ['id' => 'web2.0-1', 'type' => 'web2.0', 'position' => ['x' => 100, 'y' => 100]],
                        ['id' => 'wiki-1', 'type' => 'wiki', 'position' => ['x' => 300, 'y' => 100]],
                        ['id' => 'forum-1', 'type' => 'forum', 'position' => ['x' => 500, 'y' => 100]],
                        ['id' => 'bookmark-1', 'type' => 'bookmark', 'position' => ['x' => 200, 'y' => 250]],
                        ['id' => 'rss-1', 'type' => 'rss', 'position' => ['x' => 400, 'y' => 250]],
                    ],
                    'edges' => [
                        ['source' => 'web2.0-1', 'target' => 'bookmark-1'],
                        ['source' => 'wiki-1', 'target' => 'bookmark-1'],
                        ['source' => 'forum-1', 'target' => 'rss-1'],
                    ]
                ]
            ]
        ];

        $templateType = $this->faker->randomElement(array_keys($templateTypes));
        $template = $templateTypes[$templateType];

        return [
            'name' => $template['name'],
            'description' => $template['description'],
            'graph_json' => $template['graph_json'],
            'is_public' => $this->faker->boolean(20), // 20% chance of being public
            'created_by' => User::factory(),
            'team_id' => $this->faker->optional()->randomElement([Team::factory(), null]),
            'metadata' => [
                'category' => $this->faker->randomElement(['beginner', 'intermediate', 'advanced']),
                'estimated_time' => $this->faker->numberBetween(1, 30),
                'difficulty' => $this->faker->randomElement(['easy', 'medium', 'hard']),
                'tags' => $this->faker->words(3),
            ],
            'usage_count' => $this->faker->numberBetween(0, 100),
        ];
    }

    /**
     * Indicate that the template is public.
     */
    public function public(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_public' => true,
        ]);
    }

    /**
     * Indicate that the template is private.
     */
    public function private(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_public' => false,
        ]);
    }

    /**
     * Indicate that the template is popular.
     */
    public function popular(): static
    {
        return $this->state(fn (array $attributes) => [
            'usage_count' => $this->faker->numberBetween(50, 500),
            'is_public' => true,
        ]);
    }
}
