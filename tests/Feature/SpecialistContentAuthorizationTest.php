<?php

namespace Tests\Feature;

use App\Models\ContentItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SpecialistContentAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    private function makeAdmin(string $role): User
    {
        return User::create([
            'name' => 'Specialist Admin',
            'email' => $role.'@example.test',
            'password' => Hash::make('a-very-long-test-password'),
            'role' => $role,
            'is_active' => true,
        ]);
    }

    private function makeContent(User $author, string $type): ContentItem
    {
        return ContentItem::create([
            'title' => 'Konten Uji',
            'slug' => 'konten-uji-'.$type,
            'type' => $type,
            'excerpt' => 'Ringkasan',
            'body' => 'Isi awal',
            'status' => 'draft',
            'author_id' => $author->id,
        ]);
    }

    public function test_specialist_cannot_take_over_another_category_by_changing_submitted_type(): void
    {
        $admin = $this->makeAdmin('admin_event');
        $article = $this->makeContent($admin, 'article');

        $this->actingAs($admin)
            ->put(route('admin.content.update', $article), [
                'title' => 'Konten Diubah',
                'type' => 'event',
                'excerpt' => 'Ringkasan baru',
                'body' => 'Isi baru',
                'status' => 'draft',
            ])
            ->assertForbidden();

        $this->assertDatabaseHas('content_items', [
            'id' => $article->id,
            'type' => 'article',
            'title' => 'Konten Uji',
            'body' => 'Isi awal',
        ]);
    }

    public function test_specialist_can_update_content_in_their_own_category(): void
    {
        $admin = $this->makeAdmin('admin_event');
        $event = $this->makeContent($admin, 'event');

        $this->actingAs($admin)
            ->put(route('admin.content.update', $event), [
                'title' => 'Event Diperbarui',
                'type' => 'event',
                'excerpt' => 'Ringkasan baru',
                'body' => 'Isi baru',
                'status' => 'draft',
            ])
            ->assertRedirect(route('admin.content.index'));

        $this->assertDatabaseHas('content_items', [
            'id' => $event->id,
            'type' => 'event',
            'title' => 'Event Diperbarui',
            'body' => 'Isi baru',
        ]);
    }
}
