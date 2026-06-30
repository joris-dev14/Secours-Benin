<?php

namespace Database\Seeders;

use App\Models\PageContent;
use Illuminate\Database\Seeder;

class PageContentSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'slug' => 'accueil',
                'title' => 'Accueil',
                'subtitle' => 'Plateforme nationale de secours médical',
                'content_json' => [
                    'hero' => [
                        'title' => 'Chaque seconde compte',
                        'subtitle' => 'Secours Bénin connecte les citoyens, régulateurs et ambulanciers autour d’une réponse rapide et coordonnée.',
                    ],
                ],
                'is_active' => true,
            ],
            [
                'slug' => 'a-propos',
                'title' => 'À propos',
                'subtitle' => 'Historique, mission et vision',
                'content_json' => [
                    'hero' => [
                        'title' => 'À propos de Secours Bénin',
                        'subtitle' => 'Une plateforme moderne pensée pour réduire les délais de secours au Bénin.',
                    ],
                    'sections' => [
                        ['title' => 'Notre mission', 'body' => 'Centraliser le signalement, la régulation et l’intervention pour sauver des vies plus rapidement.'],
                        ['title' => 'Notre vision', 'body' => 'Créer un réseau de secours fiable, transparent et accessible sur l’ensemble du territoire.'],
                    ],
                ],
                'is_active' => true,
            ],
            [
                'slug' => 'fonctionnalites',
                'title' => 'Fonctionnalités',
                'subtitle' => 'Chaque acteur a son espace dédié',
                'content_json' => [
                    'hero' => [
                        'title' => 'Fonctionnalités clés',
                        'subtitle' => 'Un parcours rapide pour les citoyens, un tableau de bord réactif pour les régulateurs et des outils mobiles pour les ambulanciers.',
                    ],
                    'sections' => [
                        ['title' => 'Citoyen', 'body' => 'Signalement rapide par téléphone, géolocalisation et photo.'],
                        ['title' => 'Régulateur', 'body' => 'Réception des alertes, dispatch et suivi de la flotte.'],
                        ['title' => 'Ambulancier', 'body' => 'Mission assignée, statut en temps réel et suivi d’intervention.'],
                    ],
                ],
                'is_active' => true,
            ],
            [
                'slug' => 'partenaires',
                'title' => 'Partenaires',
                'subtitle' => 'Un réseau institutionnel et sanitaire engagé',
                'content_json' => [
                    'hero' => [
                        'title' => 'Partenaires de Secours Bénin',
                        'subtitle' => 'Des institutions publiques, des hôpitaux et des acteurs du numérique mobilisés autour du projet.',
                    ],
                    'sections' => [
                        ['title' => 'Institutions', 'body' => 'Soutien institutionnel et coordination avec l’État.'],
                        ['title' => 'Établissements de santé', 'body' => 'Réseau d’accueil et de prise en charge des patients.'],
                    ],
                ],
                'is_active' => true,
            ],
            [
                'slug' => 'mentions-legales',
                'title' => 'Mentions légales',
                'subtitle' => 'Protection des données et responsabilités',
                'content_json' => [
                    'sections' => [
                        ['title' => 'Collecte des données', 'body' => 'Les données collectées sont utilisées uniquement pour l’accompagnement de l’urgence médicale.'],
                        ['title' => 'Destinataires', 'body' => 'Elles sont accessibles au personnel compétent du SAMU et aux ambulanciers concernés.'],
                        ['title' => 'Conservation', 'body' => 'Les données sont conservées uniquement pendant la durée nécessaire à l’intervention et à la conformité réglementaire.'],
                    ],
                ],
                'is_active' => true,
            ],
        ];

        foreach ($pages as $page) {
            PageContent::updateOrCreate(['slug' => $page['slug']], $page);
        }
    }
}
