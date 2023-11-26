<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Etudiant;
use App\Models\Paiement;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    /*
    
    function getFormattedNumber(
        $value,
        $locale = 'en_US',
        $style = NumberFormatter::DECIMAL,
        $precision = 2,
        $groupIngUsed = true,
        $currencyCode = 'USD'
        ) {
            $formatter = new NumberFormatter($locale, $style);
            $formatter->setAttribute(NumberFormatter::FRACTION_DIGITS, $precision);
            $formatter->setAttribute(NumberFormatter::GROUPING_USED, $groupIngUsed);
            if($style == NumberFormatter::CURRENCY){
                $formatter->setTextAttribute(NumberFormatter::CURRENCY_CODE, $currencyCode);
            }
            return $formatter->format($value);
        }
    
    */
    public function index(){
        if(session()->has('utilisateur')){
            $utilisateur = Etudiant::find(session('utilisateur'));
            $paiements = Paiement::where([['active', true]])->orderBy('created_at', 'desc')->get();
            $etudiants = Etudiant::where([['active', true]])->orderBy('created_at', 'desc')->get();
            
            $revenu = (int)Paiement::where('active', true)->sum('montantPaye');
            
            $etudiantsSoldes = Etudiant::where('active', true)
                ->whereHas('paiements', function ($query) {
                    $query->where('montantRestant', 0);
                })
                ->orderBy('created_at', 'desc')
                ->get();

            $etudiantsNonSoldes = Etudiant::where('active', true)
                ->whereDoesntHave('paiements', function ($query) {
                    $query->where('montantRestant', 0);
                })
                ->orderBy('created_at', 'desc')
                ->get();

            $etudiantsWithoutPaiements = Etudiant::where('active', true)
                ->whereDoesntHave('paiements')
                ->orderBy('created_at', 'desc')
                ->get();

            $fiveLast = Etudiant::latest()->take(5)->get();
            $utilisateurs = Etudiant::where([['active', true], ['access', true]])->orderBy('created_at', 'desc')->get();
            $roles = Role::where([['active', true]])->orderBy('libelle', 'asc')->get();
            $menu = 'Tableau de bord';
            return view('home', compact('menu', 'utilisateur', 'etudiants', 'etudiantsSoldes', 'revenu', 'etudiantsNonSoldes', 'etudiantsWithoutPaiements', 'utilisateurs', 'paiements', 'roles', 'fiveLast'));
        }
        else {
            return redirect('deconnexion');
        }
    }

    public function profile()
    {
        if(session()->has('utilisateur')){
            $utilisateur = Etudiant::find(session('utilisateur'));
            $paiements = Paiement::where([['active', true]])->orderBy('created_at', 'desc')->get();
            $etudiants = Etudiant::where([['active', true]])->orderBy('created_at', 'desc')->get();
            $fiveLast = Etudiant::latest()->take(5)->get();
            $utilisateurs = Etudiant::where([['active', true], ['access', true]])->orderBy('created_at', 'desc')->get();
            $roles = Role::where([['active', true]])->orderBy('libelle', 'asc')->get();
            $menu = 'profil';
            return view('user.profile', compact('menu', 'utilisateur', 'etudiants', 'paiements', 'utilisateurs', 'roles', 'fiveLast'));
        }
        else {
            return redirect('deconnexion');
        }
    }
    
    public function updatePassword(Request $request)
    {

        if(session()->has('utilisateur')){
            $utilisateur = Etudiant::find(session('utilisateur'));

            $validator = validator($request->all(),
                [
                    'passwd' => 'required|min:6',
                    'newPasswd' => 'required|min:6',
                    'confNewpasswd' => 'required|min:6|same:newPasswd'
                ],
                [
                    'passwd.required' => 'Votre mot de passe actuel est requis',
                    'newPasswd.required' => 'Votre nouveau mot de passe est requis',
                    'confNewpasswd.required' => 'Confirmation du mot de passe requis',
                    'passwd.min' => 'Le mot de passe doit optenir au moins 6 caratères',
                    "newPasswd.min" => "Le mot de passe doit optenir au moins 6 caratères",
                    "confNewpasswd.min" => "Le mot de passe doit optenir au moins 6 caratères",
                    "confNewpasswd.same" => "Le mot de passe ne correspond pas au précédant"
                ]
            );

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

                $motDePasseSaisi = $request->input('passwd');
                if (password_verify($motDePasseSaisi, $utilisateur->passwd)) {
                    // Mot de passe correct
                    // Effectuer les actions souhaitées lorsque le mot de passe correspond
                    $utilisateur->passwd = Hash::make($request->input('newPasswd'));
                    $utilisateur->save();
                    if (session()->has('utilisateur')) {
                        session()->pull('utilisateur');
                    }
                    return redirect()->route('login')->with('success', 'Votre mot de passe a été modifié !');
                }
                else{
                    return redirect()->back()->with(['error' => 'Erreur dans le mot de passe actuel !']);
                }
        }
        else {
            return redirect('deconnexion');
        }

    }

    public function changePP(Request $request){
        if(session()->has('utilisateur')){
            $utilisateur = Etudiant::find(session('utilisateur'));
            //dd($request);
            if($request->ajax()){
                if($_FILES['photo']['size'] > 3145728){
                    return redirect()->back()->with('error','Photo trop lourde !');
                }
                $photo = $request->file('photo');
                $extentionFichier = $photo->getClientOriginalExtension();
                $nomDuFichier = $request->numCarteEtud.'.'.$extentionFichier;
                $dossierContentFile = public_path('assets/images/etudiants/photos/');
                $uploadPhoto = $photo->move($dossierContentFile, $nomDuFichier);
                Storage::move($dossierContentFile, 'public/webinar/', $nomDuFichier);
                $data['photo'] = $nomDuFichier;

                $utilisateur->update($data);

                if($uploadPhoto){
                    return response()->json([
                        'success' => 'image uploadée',
                        'valueimg' => $data
                    ]);  
                }
                else{
                    return redirect()->route('profile')->with('error','Impossible de modifier la photo !');
                }
                
            }
        }
        else{
            return redirect()->route('deconnexion');
        }
    }
}
