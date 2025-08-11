<?php

namespace Database\Factories;

use App\Models\Proxy;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Proxy>
 */
class ProxyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Proxy::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $types = ['http', 'https', 'socks4', 'socks5'];
        $countries = ['US', 'UK', 'CA', 'DE', 'FR', 'NL', 'SE', 'NO', 'CH', 'JP'];
        
        return [
            'ip' => $this->faker->ipv4(),
            'port' => $this->faker->numberBetween(1000, 65535),
            'username' => $this->faker->optional()->userName(),
            'password' => $this->faker->optional()->password(6, 12), // This will be encrypted automatically
            'type' => $this->faker->randomElement($types),
            'country' => $this->faker->randomElement($countries),
            'status' => $this->faker->randomElement(['active', 'inactive', 'testing']),
            'success_count' => $this->faker->numberBetween(0, 100),
            'failure_count' => $this->faker->numberBetween(0, 20),
            'last_used_at' => $this->faker->optional()->dateTimeBetween('-1 week', 'now'),
            'last_tested_at' => $this->faker->optional()->dateTimeBetween('-1 day', 'now'),
        ];
    }

    /**
     * Indicate that the proxy is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
            'last_used_at' => now(),
        ]);
    }

    /**
     * Indicate that the proxy is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'inactive',
        ]);
    }

    /**
     * Indicate that the proxy is reliable (high success rate).
     */
    public function reliable(): static
    {
        return $this->state(fn (array $attributes) => [
            'success_count' => $this->faker->numberBetween(50, 200),
            'failure_count' => $this->faker->numberBetween(0, 10),
            'status' => 'active',
        ]);
    }

    /**
     * Create proxy for specific type.
     */
    public function ofType(string $type): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => $type,
        ]);
    }

    /**
     * Create proxy for specific country.
     */
    public function forCountry(string $country): static
    {
        return $this->state(fn (array $attributes) => [
            'country' => $country,
        ]);
    }
}
