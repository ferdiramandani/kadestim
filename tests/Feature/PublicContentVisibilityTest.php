<?php

namespace Tests\Feature;

use App\Models\ContentItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicContentVisibilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_published_content_is_visible_to_guests(): void
    {
        ContentItem::create([
            'title' => 'Artikel Terbit',
            'slug' => 'artikel-terbit',
            'type' => 'article',
            'status' => 'published',
            'published_at' => now()->subMinute(),
            'excerpt' => 'Materi yang dapat dibaca publik.',
        ]);

        ContentItem::create([
            'title' => 'Artikel Draft',
            'slug' => 'artikel-draft',
            'type' => 'article',
            'status' => 'draft',
            'excerpt' => 'Materi yang belum boleh tampil.',
        ]);

        $this->get(route('education'))
            ->assertOk()
            ->assertSee('Artikel Terbit')
            ->assertDontSee('Artikel Draft');

        $this->get(route('content.show', ['type' => 'article', 'slug' => 'artikel-draft']))
            ->assertNotFound();
    }
}
