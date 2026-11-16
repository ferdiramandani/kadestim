<?php

namespace Tests\Feature;

use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    public function test_homepage_is_available_without_login(): void
    {
        $this->get('/')->assertOk()->assertSee('KADESTIM');
    }

    public function test_public_sections_are_available_without_login(): void
    {
        foreach (['/edukasi', '/event', '/galeri', '/pembelajaran', '/tentang'] as $path) {
            $this->get($path)->assertOk();
        }
    }
}
