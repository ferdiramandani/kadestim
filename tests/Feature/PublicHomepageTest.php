<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicHomepageTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_open_homepage_without_an_account(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Edukasi Kesehatan')
            ->assertSee('Event Duta')
            ->assertSee('Galeri')
            ->assertSee('Media Pembelajaran')
            ->assertSee('Materi edukasi');
    }
}
