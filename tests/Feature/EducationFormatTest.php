<?php

namespace Tests\Feature;

use App\Models\ContentItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class EducationFormatTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_publish_text_or_video_education(): void
    {
        $admin = User::create([
            'name' => 'Admin Edukasi',
            'email' => 'editor@example.test',
            'password' => Hash::make('a-very-long-test-password'),
            'role' => 'admin_education',
            'is_active' => true,
        ]);

        $this->actingAs($admin)->post(route('admin.content.store'), [
            'title' => 'Video Gizi Seimbang',
            'type' => 'article',
            'content_format' => 'video',
            'status' => 'published',
            'excerpt' => 'Ringkasan video edukasi.',
            'external_url' => 'https://youtu.be/abcdefghijk',
        ])->assertRedirect(route('admin.content.index'));

        $this->assertDatabaseHas('content_items', [
            'title' => 'Video Gizi Seimbang',
            'content_format' => 'video',
            'status' => 'published',
        ]);

        $this->get(route('education', ['format' => 'video']))
            ->assertOk()
            ->assertSee('Video Gizi Seimbang')
            ->assertSee('VIDEO');
    }

    public function test_education_admin_cannot_create_event_content(): void
    {
        $admin = User::create([
            'name' => 'Admin Edukasi',
            'email' => 'editor2@example.test',
            'password' => Hash::make('a-very-long-test-password'),
            'role' => 'admin_education',
            'is_active' => true,
        ]);

        $this->actingAs($admin)->post(route('admin.content.store'), [
            'title' => 'Event Tidak Diizinkan',
            'type' => 'event',
            'status' => 'draft',
        ])->assertForbidden();

        $this->assertDatabaseMissing('content_items', ['title' => 'Event Tidak Diizinkan']);
    }

    public function test_event_and_education_cards_show_only_short_summaries(): void
    {
        $longSummary = str_repeat('Ringkasan informasi kegiatan. ', 20);

        ContentItem::create([
            'title' => 'Event Duta Kesehatan',
            'slug' => 'event-duta-kesehatan',
            'type' => 'event',
            'excerpt' => $longSummary,
            'status' => 'published',
            'published_at' => now()->subMinute(),
        ]);

        $this->get(route('events'))
            ->assertOk()
            ->assertSee('Selengkapnya')
            ->assertDontSee($longSummary);
    }
}
