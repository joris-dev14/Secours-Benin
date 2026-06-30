<?php

namespace Tests\Feature;

use App\Models\PageContent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_pages_use_database_backed_content(): void
    {
        PageContent::create([
            'slug' => 'a-propos',
            'title' => 'À propos',
            'subtitle' => 'Contenu dynamique',
            'content_json' => json_encode([
                'hero' => ['title' => 'Titre dynamique', 'subtitle' => 'Sous-titre dynamique'],
                'context' => ['title' => 'Contexte'],
            ]),
            'is_active' => true,
        ]);

        PageContent::create([
            'slug' => 'mentions-legales',
            'title' => 'Mentions légales',
            'subtitle' => 'Mentions',
            'content_json' => json_encode(['sections' => [['title' => 'Collecte', 'body' => 'Données']]]),
            'is_active' => true,
        ]);

        $response = $this->get('/a-propos');
        $response->assertStatus(200);
        $response->assertSee('Titre dynamique');

        $response = $this->get('/mentions-legales');
        $response->assertStatus(200);
        $response->assertSee('Collecte');

        $response = $this->get('/contact');
        $response->assertStatus(200);
        $response->assertSee('Formulaire de contact');
    }
}
