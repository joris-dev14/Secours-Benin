<?php
namespace App\Http\Controllers;

use App\Models\OtpCode;
use App\Models\Citoyen;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Services\SmsService;

class OtpController extends Controller
{
    private $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    public function envoyer(Request $request)
{
    try{
        $request->validate(['phone' => 'required']);

        $rawPhone = $request->input('phone');
        $cleanPhone = preg_replace('/\D+/', '', $rawPhone);

        // Retirer le 01 au début s'il existe
        $localNumber = preg_replace('/^01/', '', $cleanPhone);

        if (!preg_match('/^\d{8}$/', $localNumber)) {
            return response()->json([
                'success' => false,
                'message' => 'Veuillez entrer un numéro valide de 8 chiffres ou commençant par 01.',
            ], 422);
        }

        $telephone = '+229' . $localNumber;

        $code = rand(1000, 9999);

        OtpCode::create([
            'telephone' => $telephone,
            'code'      => $code,
            'utilise'   => false,
            'expire_a'  => Carbon::now()->addMinutes(5),
        ]);
        
        $sent = $this->smsService->send(
            $telephone,
            "Votre code de connexion : {$code}. Valable 10 minutes."
        );

        $request->session()->put('telephone', $telephone);
        $sandbox = config('services.africastalking.username') === 'sandbox';

        if ($sent || $sandbox) {
            return response()->json([
                'success' => true,
                'message' => 'Code envoyé avec succès.',
                'code' => $code,
                'phone' => $telephone,
                'sandbox' => $sandbox,
            ]);
        } else {
            return response()->json(['success' => false, 'message' => 'Échec de l\'envoi du code. Veuillez réessayer.']);
        }
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage(),  // affiche l'erreur réelle
        ], 500);
    }
}
    public function valider(Request $request)
{
    $otp = OtpCode::where('telephone', $request->session()->get('telephone'))
        ->where('code', $request->code)
        ->where('utilise', false)
        ->where('expire_a', '>=', Carbon::now())
        ->latest()
        ->first();

    if (!$otp) {
        return response()->json(['success' => false, 'message' => 'Code invalide ou expiré']);
    }

    $otp->update(['utilise' => true]);
    $citoyen = Citoyen::firstOrCreate(
        ['telephone' => $request->session()->get('telephone')]
    );

    session(['citoyen_id' => $citoyen->id]);
    return response()->json(['success' => true]);
}
}