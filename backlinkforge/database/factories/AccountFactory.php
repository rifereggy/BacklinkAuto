<?php

namespace Database\Factories;

use App\Models\Account;
use App\Models\Campaign;
use App\Models\Proxy;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Account>
 */
class AccountFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Account::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $providers = ['wordpress', 'blogger', 'medium', 'tumblr', 'weebly'];
        $provider = $this->faker->randomElement($providers);
        
        return [
            'campaign_id' => Campaign::factory(),
            'provider' => $provider,
            'username' => $this->faker->userName(),
            'email' => $this->faker->email(),
            'password' => $this->faker->password(8, 12), // This will be encrypted automatically
            'proxy_id' => $this->faker->optional()->randomElement([Proxy::factory(), null]),
            'status' => $this->faker->randomElement(['pending', 'created', 'active', 'suspended']),
            'metadata' => [
                'site_url' => $this->faker->url(),
                'registration_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
                'last_login' => $this->faker->optional()->dateTimeBetween('-1 month', 'now'),
                'posts_count' => $this->faker->numberBetween(0, 50),
            ],
            'last_used_at' => $this->faker->optional()->dateTimeBetween('-1 week', 'now'),
        ];
    }

    /**
     * Indicate that the account is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
            'last_used_at' => now(),
        ]);
    }

    /**
     * Indicate that the account is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'last_used_at' => null,
        ]);
    }

    /**
     * Indicate that the account is suspended.
     */
    public function suspended(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'suspended',
        ]);
    }

    /**
     * Create account for specific provider.
     */
    public function forProvider(string $provider): static
    {
        return $this->state(fn (array $attributes) => [
            'provider' => $provider,
        ]);
    }
}
