<?php

namespace Database\Seeders;

use App\Models\Template;
use App\Models\User;
use Illuminate\Database\Seeder;

class TemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first() ?? User::factory()->create();

        $templates = [
            [
                'name' => 'Web2.0 Pyramid',
                'description' => 'Classic web2.0 pyramid structure for link building campaigns',
                'graph_json' => [
                    'nodes' => [
                        [
                            'id' => 'web2.0-1',
                            'type' => 'web2.0',
                            'position' => ['x' => 100, 'y' => 100],
                            'data' => [
                                'name' => 'WordPress Blog 1',
                                'provider' => 'wordpress',
                                'url' => 'https://example1.wordpress.com',
                                'settings' => [
                                    'posts_per_day' => 1,
                                    'content_type' => 'article',
                                ]
                            ]
                        ],
                        [
                            'id' => 'web2.0-2',
                            'type' => 'web2.0',
                            'position' => ['x' => 300, 'y' => 100],
                            'data' => [
                                'name' => 'Blogger Site',
                                'provider' => 'blogger',
                                'url' => 'https://example.blogspot.com',
                                'settings' => [
                                    'posts_per_day' => 1,
                                    'content_type' => 'article',
                                ]
                            ]
                        ],
                        [
                            'id' => 'wiki-1',
                            'type' => 'wiki',
                            'position' => ['x' => 200, 'y' => 250],
                            'data' => [
                                'name' => 'MediaWiki Page',
                                'provider' => 'mediawiki',
                                'url' => 'https://example.wiki.com',
                                'settings' => [
                                    'content_type' => 'wiki_article',
                                ]
                            ]
                        ]
                    ],
                    'edges' => [
                        [
                            'id' => 'edge-1',
                            'source' => 'web2.0-1',
                            'target' => 'wiki-1',
                        ],
                        [
                            'id' => 'edge-2',
                            'source' => 'web2.0-2',
                            'target' => 'wiki-1',
                        ]
                    ]
                ],
                'is_public' => true,
                'metadata' => [
                    'category' => 'beginner',
                    'estimated_time' => 7,
                    'difficulty' => 'easy',
                    'tags' => ['web2.0', 'pyramid', 'beginner'],
                ]
            ],
            [
                'name' => 'Forum Network',
                'description' => 'Forum-based link building network with profile links',
                'graph_json' => [
                    'nodes' => [
                        [
                            'id' => 'forum-1',
                            'type' => 'forum',
                            'position' => ['x' => 100, 'y' => 100],
                            'data' => [
                                'name' => 'Tech Forum',
                                'provider' => 'phpbb',
                                'url' => 'https://techforum.com',
                                'settings' => [
                                    'posts_per_day' => 2,
                                    'content_type' => 'forum_post',
                                ]
                            ]
                        ],
                        [
                            'id' => 'forum-2',
                            'type' => 'forum',
                            'position' => ['x' => 300, 'y' => 100],
                            'data' => [
                                'name' => 'Business Forum',
                                'provider' => 'vbulletin',
                                'url' => 'https://businessforum.com',
                                'settings' => [
                                    'posts_per_day' => 2,
                                    'content_type' => 'forum_post',
                                ]
                            ]
                        ],
                        [
                            'id' => 'profile-1',
                            'type' => 'profile',
                            'position' => ['x' => 200, 'y' => 250],
                            'data' => [
                                'name' => 'Social Profile',
                                'provider' => 'linkedin',
                                'url' => 'https://linkedin.com/in/example',
                                'settings' => [
                                    'content_type' => 'profile_bio',
                                ]
                            ]
                        ]
                    ],
                    'edges' => [
                        [
                            'id' => 'edge-1',
                            'source' => 'forum-1',
                            'target' => 'profile-1',
                        ],
                        [
                            'id' => 'edge-2',
                            'source' => 'forum-2',
                            'target' => 'profile-1',
                        ]
                    ]
                ],
                'is_public' => true,
                'metadata' => [
                    'category' => 'intermediate',
                    'estimated_time' => 14,
                    'difficulty' => 'medium',
                    'tags' => ['forum', 'profile', 'intermediate'],
                ]
            ],
            [
                'name' => 'Mixed Tier Structure',
                'description' => 'Advanced mixed tier structure with various platforms',
                'graph_json' => [
                    'nodes' => [
                        [
                            'id' => 'web2.0-1',
                            'type' => 'web2.0',
                            'position' => ['x' => 100, 'y' => 100],
                            'data' => [
                                'name' => 'Medium Blog',
                                'provider' => 'medium',
                                'url' => 'https://medium.com/@example',
                                'settings' => [
                                    'posts_per_day' => 1,
                                    'content_type' => 'article',
                                ]
                            ]
                        ],
                        [
                            'id' => 'wiki-1',
                            'type' => 'wiki',
                            'position' => ['x' => 300, 'y' => 100],
                            'data' => [
                                'name' => 'Wikidot Page',
                                'provider' => 'wikidot',
                                'url' => 'https://example.wikidot.com',
                                'settings' => [
                                    'content_type' => 'wiki_article',
                                ]
                            ]
                        ],
                        [
                            'id' => 'forum-1',
                            'type' => 'forum',
                            'position' => ['x' => 500, 'y' => 100],
                            'data' => [
                                'name' => 'XenForo Forum',
                                'provider' => 'xenforo',
                                'url' => 'https://forum.example.com',
                                'settings' => [
                                    'posts_per_day' => 1,
                                    'content_type' => 'forum_post',
                                ]
                            ]
                        ],
                        [
                            'id' => 'bookmark-1',
                            'type' => 'bookmark',
                            'position' => ['x' => 200, 'y' => 250],
                            'data' => [
                                'name' => 'Delicious Bookmark',
                                'provider' => 'delicious',
                                'url' => 'https://delicious.com',
                                'settings' => [
                                    'content_type' => 'bookmark',
                                ]
                            ]
                        ],
                        [
                            'id' => 'rss-1',
                            'type' => 'rss',
                            'position' => ['x' => 400, 'y' => 250],
                            'data' => [
                                'name' => 'RSS Directory',
                                'provider' => 'rss_directory',
                                'url' => 'https://rss.example.com',
                                'settings' => [
                                    'content_type' => 'rss_submission',
                                ]
                            ]
                        ]
                    ],
                    'edges' => [
                        [
                            'id' => 'edge-1',
                            'source' => 'web2.0-1',
                            'target' => 'bookmark-1',
                        ],
                        [
                            'id' => 'edge-2',
                            'source' => 'wiki-1',
                            'target' => 'bookmark-1',
                        ],
                        [
                            'id' => 'edge-3',
                            'source' => 'forum-1',
                            'target' => 'rss-1',
                        ]
                    ]
                ],
                'is_public' => true,
                'metadata' => [
                    'category' => 'advanced',
                    'estimated_time' => 21,
                    'difficulty' => 'hard',
                    'tags' => ['mixed', 'advanced', 'comprehensive'],
                ]
            ]
        ];

        foreach ($templates as $templateData) {
            Template::create(array_merge($templateData, [
                'created_by' => $user->id,
            ]));
        }
    }
}
