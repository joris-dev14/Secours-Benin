<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\PageContent;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function home()
    {
        $content = PageContent::where('slug', 'accueil')->where('is_active', true)->first();
        $ambulancesCount = \App\Models\Ambulance::count();
        $communesCount = \App\Models\Commune::count();
        $alertesCount = \App\Models\Alerte::count();
        $avgResponseTime = \App\Models\Mission::whereNotNull('termine_a')->where('statut', 'terminee')->avg(\DB::raw('TIMESTAMPDIFF(MINUTE, created_at, termine_a)'));

        return view('welcome', compact('content', 'ambulancesCount', 'communesCount', 'alertesCount', 'avgResponseTime'));
    }

    public function show(Request $request)
    {
        $slug = $request->path();
        $slug = $slug === '/' ? 'accueil' : str_replace('/', '', $slug);

        if ($slug === 'contact') {
            return view('contact');
        }

        $content = PageContent::where('slug', $slug)->where('is_active', true)->first();

        if (!$content) {
            $fallback = [
                'title' => 'Secours Bénin',
                'subtitle' => 'Plateforme de gestion des urgences médicales',
                'content_json' => [],
            ];
            $content = (object) $fallback;
        }

        return view('pages.dynamic', compact('content'));
    }

    public function legal()
    {
        $content = PageContent::where('slug', 'mentions-legales')->where('is_active', true)->first();

        if (!$content) {
            $content = (object) [
                'title' => 'Mentions légales',
                'subtitle' => 'Protection des données et responsabilités',
                'content_json' => [
                    'sections' => [
                        [
                            'title' => 'Collecte des données',
                            'body' => 'Les données collectées sont utilisées uniquement pour l’accompagnement de l’urgence médicale.',
                        ],
                        [
                            'title' => 'Destinataires',
                            'body' => 'Elles sont accessibles au personnel compétent du SAMU et aux ambulanciers concernés.',
                        ],
                        [
                            'title' => 'Conservation',
                            'body' => 'Les données sont supprimées selon les règles applicables et les délais de conservation.',
                        ],
                    ],
                ],
            ];
        }

        return view('pages.legal', compact('content'));
    }

    public function contact(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:100',
            'email' => 'required|email|max:255',
            'telephone' => 'nullable|string|max:20',
            'sujet' => 'required|string|max:100',
            'message' => 'required|string|max:2000',
            'consent' => 'accepted',
        ]);

        ContactMessage::create([
            'nom' => $request->nom,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'sujet' => $request->sujet,
            'message' => $request->message,
            'consent' => (bool) $request->boolean('consent'),
        ]);

        return redirect('/contact')->with('success', 'Votre message a bien été envoyé.');
    }
}
