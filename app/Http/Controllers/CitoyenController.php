<?php
namespace App\Http\Controllers;

use App\Models\Citoyen;
use Illuminate\Http\Request;

class CitoyenController extends Controller
{
    public function auth()
    {
        return view('citoyen.auth');
    }

    public function consentement()
    {
        return view('citoyen.consentement');
    }

    public function validerConsentement(Request $request)
    {
        $citoyen = Citoyen::where('telephone', $request->session()->get('telephone'))->first();
        if ($citoyen) {
            $citoyen->update(['consentement' => true]);
        }
        return redirect('/citoyen/nouvelle-alerte');
    }
}