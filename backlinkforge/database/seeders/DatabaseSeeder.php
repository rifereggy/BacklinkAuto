<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Setting;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create users first
        $this->call([
            UserSeeder::class,
        ]);

        // Create templates
        $this->call([
            TemplateSeeder::class,
        ]);

        // Create default settings
        $this->createDefaultSettings();

        // Create sample data for testing
        $this->createSampleData();
    }

    /**
     * Create default application settings
     */
    private function createDefaultSettings(): void
    {
        $settings = [
            [
                'key' => 'app_name',
                'value' => 'BacklinkForge',
                'encrypted' => false,
                'type' => 'string',
                'description' => 'Application name',
                'group' => 'general',
            ],
            [
                'key' => 'max_campaigns_per_user',
                'value' => '10',
                'encrypted' => false,
                'type' => 'integer',
                'description' => 'Maximum campaigns per user',
                'group' => 'limits',
            ],
            [
                'key' => 'max_accounts_per_campaign',
                'value' => '50',
                'encrypted' => false,
                'type' => 'integer',
                'description' => 'Maximum accounts per campaign',
                'group' => 'limits',
            ],
            [
                'key' => 'default_delay_between_posts',
                'value' => '300',
                'encrypted' => false,
                'type' => 'integer',
                'description' => 'Default delay between posts (seconds)',
                'group' => 'automation',
            ],
            [
                'key' => 'max_retries_per_job',
                'value' => '3',
                'encrypted' => false,
                'type' => 'integer',
                'description' => 'Maximum retries per job',
                'group' => 'automation',
            ],
            [
                'key' => 'captcha_api_key',
                'value' => '',
                'encrypted' => true,
                'type' => 'string',
                'description' => '2Captcha API key',
                'group' => 'captcha',
            ],
            [
                'key' => 'openai_api_key',
                'value' => '',
                'encrypted' => true,
                'type' => 'string',
                'description' => 'OpenAI API key for content generation',
                'group' => 'ai',
            ],
            [
                'key' => 'enable_ai_content',
                'value' => 'false',
                'encrypted' => false,
                'type' => 'boolean',
                'description' => 'Enable AI content generation',
                'group' => 'ai',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::firstOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }

    /**
     * Create sample data for testing
     */
    private function createSampleData(): void
    {
        // Create sample campaigns, accounts, and proxies for demo user
        $demoUser = User::where('email', 'demo@backlinkforge.com')->first();
        
        if ($demoUser) {
            // Create sample campaigns
            $campaigns = \App\Models\Campaign::factory(3)
                ->for($demoUser)
                ->for($demoUser->currentTeam)
                ->create();

            // Create sample proxies
            $proxies = \App\Models\Proxy::factory(5)
                ->active()
                ->create();

            // Create sample accounts for each campaign
            foreach ($campaigns as $campaign) {
                \App\Models\Account::factory(3)
                    ->for($campaign)
                    ->active()
                    ->create();
            }

            // Create sample content items
            foreach ($campaigns as $campaign) {
                \App\Models\ContentItem::factory(5)
                    ->for($campaign)
                    ->ready()
                    ->create();
            }
        }
    }
}
