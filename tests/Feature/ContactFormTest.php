<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactFormTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_form_submits_and_stores_message(): void
    {
        $response = $this->post('/contact', [
            'nom' => 'Jean Dupont',
            'email' => 'jean@example.com',
            'telephone' => '+22997000000',
            'sujet' => 'Support technique',
            'message' => 'Bonjour, j’ai besoin d’aide.',
            'consent' => 'on',
        ]);

        $response->assertRedirect('/contact');
        $response->assertSessionHas('success', 'Votre message a bien été envoyé.');
        $this->assertDatabaseHas('contact_messages', [
            'email' => 'jean@example.com',
            'sujet' => 'Support technique',
        ]);
    }
}
