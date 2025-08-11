<?php

namespace Database\Factories;

use App\Models\ContentItem;
use App\Models\Campaign;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ContentItem>
 */
class ContentItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ContentItem::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $contentTypes = ['article', 'comment', 'profile', 'forum_post', 'wiki_article'];
        $contentType = $this->faker->randomElement($contentTypes);
        
        // Generate spintax content
        $rawSpintax = $this->generateSpintax($contentType);
        
        // Generate processed content
        $generatedText = $this->processSpintax($rawSpintax);
        
        return [
            'campaign_id' => Campaign::factory(),
            'raw_spintax' => $rawSpintax,
            'generated_text' => $generatedText,
            'content_type' => $contentType,
            'ai_used' => $this->faker->boolean(30), // 30% chance of using AI
            'ai_provider' => $this->faker->optional()->randomElement(['openai', 'claude', 'gemini']),
            'tokens' => $this->faker->optional()->numberBetween(100, 2000),
            'metadata' => [
                'word_count' => str_word_count($generatedText),
                'readability_score' => $this->faker->numberBetween(60, 90),
                'keywords' => $this->faker->words(5),
                'target_url' => $this->faker->url(),
            ],
            'status' => $this->faker->randomElement(['draft', 'ready', 'used']),
        ];
    }

    /**
     * Generate spintax content based on content type
     */
    private function generateSpintax(string $contentType): string
    {
        $spintaxTemplates = [
            'article' => [
                'title' => '{Great|Amazing|Excellent|Outstanding} {Guide|Article|Post|Resource} on {SEO|Digital Marketing|Link Building|Content Marketing}',
                'content' => 'This is a {fantastic|wonderful|excellent|amazing} {article|post|guide|resource} about {SEO|digital marketing|link building}. {You should|I recommend|It\'s important to} {read|check out|visit} this {website|site|page} for more {information|details|insights}.',
            ],
            'comment' => [
                'content' => '{Thanks|Thank you} for {sharing|posting} this {great|useful|helpful} {information|content|article}. {I found|This provides} {valuable|useful|helpful} {insights|information|tips}. {Check out|Visit} this {site|website} for more {details|info}.',
            ],
            'profile' => [
                'bio' => '{Professional|Experienced|Skilled} {marketer|consultant|specialist} with {expertise|experience} in {SEO|digital marketing|link building}. {Passionate|Dedicated} about {helping|assisting} {businesses|clients} {grow|succeed}. {Visit|Check out} my {website|site} for more {information|details}.',
            ],
            'forum_post' => [
                'title' => '{Question|Help needed|Advice} about {SEO|Link Building|Digital Marketing}',
                'content' => '{Hi everyone|Hello|Greetings}, I\'m {looking for|seeking} {advice|help|guidance} on {SEO|link building|digital marketing}. {I found|I came across} this {great|useful} {resource|website|article} that {might help|could be useful}. {What do you think|Any thoughts}?',
            ],
            'wiki_article' => [
                'title' => '{SEO|Digital Marketing|Link Building} - {Complete Guide|Overview|Introduction}',
                'content' => '{SEO|Digital Marketing|Link Building} is a {crucial|important|essential} {aspect|component} of {online marketing|digital strategy}. {This article|This guide} {provides|offers} {comprehensive|detailed} {information|insights} on {the topic|this subject}. {For more details|Additional information} {visit|check out} this {website|resource}.',
            ],
        ];

        $template = $spintaxTemplates[$contentType] ?? $spintaxTemplates['article'];
        
        if (isset($template['title']) && isset($template['content'])) {
            return $template['title'] . "\n\n" . $template['content'];
        }
        
        return $template['content'] ?? $template['title'] ?? 'Default content';
    }

    /**
     * Process spintax by randomly selecting one option from each bracket
     */
    private function processSpintax(string $spintax): string
    {
        return preg_replace_callback('/\{([^}]+)\}/', function($matches) {
            $options = explode('|', $matches[1]);
            return trim($this->faker->randomElement($options));
        }, $spintax);
    }

    /**
     * Indicate that the content is ready.
     */
    public function ready(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'ready',
        ]);
    }

    /**
     * Indicate that the content is used.
     */
    public function used(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'used',
        ]);
    }

    /**
     * Indicate that the content was generated by AI.
     */
    public function aiGenerated(): static
    {
        return $this->state(fn (array $attributes) => [
            'ai_used' => true,
            'ai_provider' => $this->faker->randomElement(['openai', 'claude', 'gemini']),
            'tokens' => $this->faker->numberBetween(100, 2000),
        ]);
    }

    /**
     * Create content for specific type.
     */
    public function ofType(string $contentType): static
    {
        return $this->state(fn (array $attributes) => [
            'content_type' => $contentType,
        ]);
    }
}
