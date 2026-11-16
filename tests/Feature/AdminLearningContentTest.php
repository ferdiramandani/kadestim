<?php

namespace Tests\Feature;

use App\Models\ContentItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminLearningContentTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::create([
            'name' => 'Admin KADESTIM',
            'email' => 'admin-learning@example.test',
            'password' => Hash::make('a-very-long-test-password'),
            'role' => 'admin',
            'is_active' => true,
        ]);
    }

    public function test_admin_can_create_a_scenario_learning_module(): void
    {
        $this->actingAs($this->admin())->post(route('admin.content.store'), [
            'title' => 'Kebiasaan Sehat Sebelum Makan',
            'type' => 'learning',
            'status' => 'draft',
            'scenario' => [
                'audience' => 'children',
                'topic' => 'Kebersihan diri',
                'intro' => 'Memahami kebiasaan bersih.',
                'situation' => 'Seorang anak selesai bermain dan akan makan.',
                'supporting_info' => ['Tangan dapat membawa kotoran dan kuman.'],
                'question' => 'Apa yang sebaiknya dilakukan?',
                'choices' => [
                    ['label' => 'Langsung makan', 'feedback' => 'Tangan sebaiknya dibersihkan dahulu.'],
                    ['label' => 'Mencuci tangan dengan sabun', 'feedback' => 'Ini membantu membersihkan kotoran dan kuman.', 'is_recommended' => '1'],
                ],
                'summary' => 'Biasakan mencuci tangan dengan sabun sebelum makan.',
            ],
        ])->assertRedirect(route('admin.content.index'));

        $this->assertDatabaseHas('content_items', [
            'title' => 'Kebiasaan Sehat Sebelum Makan',
            'type' => 'learning',
            'status' => 'draft',
        ]);

        $item = ContentItem::where('title', 'Kebiasaan Sehat Sebelum Makan')->firstOrFail();
        $this->assertSame('Mencuci tangan dengan sabun', $item->scenario_data['choices'][1]['label']);
        $this->assertTrue($item->scenario_data['choices'][1]['is_recommended']);
        $this->assertSame('children', $item->scenario_data['audience']);
        $this->assertSame('Kebersihan diri', $item->scenario_data['topic']);
    }

    public function test_admin_can_create_non_learning_content_without_scenario_fields(): void
    {
        $this->actingAs($this->admin())->post(route('admin.content.store'), [
            'title' => 'Edukasi Gizi Seimbang',
            'type' => 'article',
            'status' => 'draft',
            'excerpt' => 'Materi pengenalan gizi seimbang.',
            'body' => 'Konten edukasi.',
        ])->assertRedirect(route('admin.content.index'));

        $this->assertDatabaseHas('content_items', [
            'title' => 'Edukasi Gizi Seimbang',
            'type' => 'article',
            'status' => 'draft',
            'scenario_data' => null,
        ]);
    }

    public function test_admin_can_upload_a_cover_image_for_content(): void
    {
        Storage::fake('public');

        $this->actingAs($this->admin())->post(route('admin.content.store'), [
            'title' => 'Dokumentasi Kegiatan',
            'type' => 'gallery',
            'status' => 'draft',
            'cover_image_file' => UploadedFile::fake()->create('cover.jpg', 100, 'image/jpeg'),
        ])->assertRedirect(route('admin.content.index'));

        $item = ContentItem::where('title', 'Dokumentasi Kegiatan')->firstOrFail();
        Storage::disk('public')->assertExists($item->cover_image);
    }

    public function test_learning_module_requires_exactly_one_recommended_choice(): void
    {
        $this->actingAs($this->admin())->from('/admin/konten/create')->post(route('admin.content.store'), [
            'title' => 'Skenario Uji',
            'type' => 'learning',
            'status' => 'draft',
            'scenario' => [
                'audience' => 'teens',
                'topic' => 'Kesehatan remaja',
                'intro' => 'Pembuka',
                'situation' => 'Situasi',
                'supporting_info' => [],
                'question' => 'Pertanyaan?',
                'choices' => [
                    ['label' => 'Pilihan A', 'feedback' => 'Ulasan A', 'is_recommended' => '1'],
                    ['label' => 'Pilihan B', 'feedback' => 'Ulasan B', 'is_recommended' => '1'],
                ],
                'summary' => 'Rangkuman',
            ],
        ])->assertSessionHasErrors('scenario.choices');

        $this->assertDatabaseMissing('content_items', ['title' => 'Skenario Uji']);
    }
}
