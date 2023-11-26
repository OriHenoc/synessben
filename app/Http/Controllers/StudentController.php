<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Etudiant;
use App\Models\Paiement;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Console\Exception\InvalidArgumentException;

class StudentController extends Controller
{
    public function index(Request $request){
        if(session()->has('utilisateur')){
            $utilisateur = Etudiant::find(session('utilisateur'));
            $paiements = Paiement::where([['active', true]])->orderBy('created_at', 'desc')->get();
            $etudiants = Etudiant::where([['active', true]])->orderBy('created_at', 'desc')->get();
            $fiveLast = Etudiant::where('active', true)->latest()->take(5)->get();
            $utilisateurs = Etudiant::where([['active', true], ['access', true]])->orderBy('created_at', 'desc')->get();
            $roles = Role::where([['active', true]])->orderBy('libelle', 'asc')->get();
            $menu = 'Etudiants';
            return view('management.students', compact('menu', 'utilisateur', 'roles', 'etudiants', 'paiements', 'fiveLast', 'utilisateurs'));
        }
        else {
            return redirect('deconnexion');
        }
    }

    function generateRandomPassword($minLength = 6, $maxLength = 8)
    {
        $uppercase = 'ABCDEFGHJKLMNPQRTUVWXYZ';
        $lowercase = 'abcdefghjklmnpqrtuvwxyz';
        $numbers = '12346789';
        $specialChars = '!@#$%&*+=;?';

        $allChars = $uppercase . $lowercase . $numbers . $specialChars;
        $allCharsLength = strlen($allChars);

        if ($minLength > $maxLength || $minLength < 6 || $maxLength > 8) {
            throw new InvalidArgumentException('Invalid password length range.');
        }

        $passwordLength = mt_rand($minLength, $maxLength);
        $password = '';

        for ($i = 0; $i < $passwordLength; $i++) {
            $randomChar = $allChars[mt_rand(0, $allCharsLength - 1)];

            // non-repetition
            while (strpos($password, $randomChar) !== false) {
                $randomChar = $allChars[mt_rand(0, $allCharsLength - 1)];
            }

            $password .= $randomChar;
        }

        return $password;
    }

    public function createStudent(Request $request)
    {
        if(session()->has('utilisateur')){
        
            $utilisateur = Etudiant::find(session('utilisateur'));

            $validator = validator($request->all(),
                [
                    'nom' => 'required',
                    'prenoms' => 'required',
                    'datenais' => 'required',
                    'niveau' => 'required',
                    'numCarteEtud' => 'required|unique:etudiants',
                    'email' => 'required|email|unique:etudiants',
                    'telephone' => 'required|unique:etudiants',
                    'username' => 'unique:etudiants',
                ],
                [
                    'nom.required' => 'Le nom est requis',
                    'prenoms.required' => 'Le(s) prénom(s) sont requis',
                    'datenais.required' => 'La date de naissance est requise',
                    'niveau.required' => 'Le niveau d\'étude est requis',
                    'numCarteEtud.required' => 'Le numéro de carte étudiant est requis',
                    'email.required' => 'L\'adresse e-mail est requise',
                    'telephone.required' => 'Le numéro de téléphone est requis',
                    'telephone.unique' => 'Ce numéro existe déja',
                    'email.unique' => 'Cet e-mail existe déja',
                    "email.email" => "Cet email n'est pas un email valide",
                    'numCarteEtud.unique' => 'Ce numéro de carte étudiant existe déja',
                    'username.unique' => 'Ce nom d\'utilisateur existe déja'
                ]
            );

        if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $data = $request->all();
            
            $photo = $request->file('photo');
            $extentionFichier = $photo->getClientOriginalExtension();
            $nomDuFichier = $request->numCarteEtud.'.'.$extentionFichier;
            $dossierContentFile = public_path('assets/images/etudiants/photos/');
            $uploadPhoto = $photo->move($dossierContentFile, $nomDuFichier);
            Storage::move($dossierContentFile, 'public/webinar/', $nomDuFichier);
            $data['photo'] = $nomDuFichier;

            $timestamp = strtotime($request->datenais);
            $data['datenais'] = date('Y-m-d', $timestamp);

            if($request->access == "on"){
                $data['access'] = true;
            }

            if($request->roleID == null){
                $data['roleID'] = 1;
            }
            else{
                $mdp = $this->generateRandomPassword();
                $data['passwd'] = Hash::make($mdp);
            }

            $etudiant = Etudiant::create($data);

            if($etudiant->access){
                Mail::send([], [], function (Message $message) use ($etudiant, $mdp) {
                    $message->to($etudiant->email)
                        ->subject('Bienvenue sur la plateforme du SYNESS-BEN')
                        ->html(
                            '<h3>'.
                            $etudiant->nom.
                            ' '.
                            $etudiant->prenoms.
                            ', vous êtes inscrit sur la plateforme du SYNESS-BEN. <br><br> Vos paramètres de connexion sont : <br><br> Nom d\'utilisateur : '.
                            $etudiant->username.
                            ' <br> Mot de passe : '.
                            $mdp.
                            ' <br><br> Veuillez vous connecter ici : <a href="https://www.synessben.committeam.com">SYNESS-BEN</a>
                            </h3>'
                        );
                });
            }
            else{
                Mail::send([], [], function (Message $message) use ($etudiant) {
                    $message->to($etudiant->email)
                        ->subject('Enregistrement sur la plateforme du SYNESS-BEN')
                        ->html(
                            '<h3>'.
                            $etudiant->nom.
                            ' '.
                            $etudiant->prenoms.
                            ', vous êtes enregistré sur la plateforme du SYNESS-BEN.
                            </h3>'
                        );
                });
            }

            return redirect()->route('students')->with('success', 'Nouveau étudiant ajouté avec succès !');
        }
        else {
            return redirect('deconnexion');
        }
    }

    public function getDetails($id)
    {
        if(session()->has('utilisateur')){
            $utilisateur = Etudiant::find(session('utilisateur'));
            $etudiant = Etudiant::find($id);
            if (!$etudiant) {
                return response()->json(['error' => 'Étudiant non trouvé'], 404);
            }

            if($etudiant->access){
                $access = "Oui";
            }else{
                $access = "Non";
            }

            $datenais = date('d/m/Y', $etudiant->datenai);

            return response()->json([
                'nom' => $etudiant->nom,
                'prenoms' => $etudiant->prenoms,
                'email' => $etudiant->email,
                'datenais' => $datenais,
                'niveau' => $etudiant->niveau,
                'numCarteEtud' => $etudiant->numCarteEtud,
                'email' => $etudiant->email,
                'telephone' => $etudiant->telephone,
                'access' => $access,
                'username' => $etudiant->username,
                'role' => $etudiant->role->libelle
            ]);
        }
        else {
            return redirect('deconnexion');
        }
    }

    public function getForUpdate($id){

        if(session()->has('utilisateur')){
            $utilisateur = Etudiant::find(session('utilisateur'));
            $etudiant = Etudiant::find($id);
            if (!$etudiant) {
                return redirect()->back()->with(['error' => 'Étudiant non trouvé']);
            }

            $etudiants = Etudiant::where([['active', true]])->orderBy('created_at', 'desc')->get();
            $fiveLast = Etudiant::where('active', true)->latest()->take(5)->get();
            $utilisateurs = Etudiant::where([['active', true], ['access', true]])->orderBy('created_at', 'desc')->get();
            $paiements = Paiement::where([['active', true]])->orderBy('created_at', 'desc')->get();
            $roles = Role::where([['active', true]])->orderBy('libelle', 'asc')->get();
            $menu = 'Etudiants';
            return view('management.studentsUpdate', compact('menu', 'etudiant', 'etudiants', 'paiements', 'utilisateur', 'utilisateurs', 'fiveLast', 'roles'));
        }
        else{
            return redirect()->route('deconnexion');
        }
    }

    public function updateUser(Request $request)
    {
        if(session()->has('utilisateur')){
                
            $utilisateur = Etudiant::find(session('utilisateur'));
            
            $etudiant = Etudiant::find($request->id);

            $validator = validator($request->all(),
            [
                'nom' => 'required',
                'prenoms' => 'required',
                'datenais' => 'required',
                'niveau' => 'required',
                'numCarteEtud' => 'required',
                'email' => 'required|email',
                'telephone' => 'required',
            ],
            [
                'nom.required' => 'Le nom est requis',
                'prenoms.required' => 'Le(s) prénom(s) sont requis',
                'datenais.required' => 'La date de naissance est requise',
                'niveau.required' => 'Le niveau d\'étude est requis',
                'numCarteEtud.required' => 'Le numéro de carte étudiant est requis',
                'email.required' => 'L\'adresse e-mail est requise',
                'telephone.required' => 'Le numéro de téléphone est requis',
                "email.email" => "Cet email n'est pas un email valide",
            ]
            );

        if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

        //verification des unicités

        $numCardUnique = Etudiant::where('numCarteEtud', $request->numCarteEtud)->first();
        $emailUnique = Etudiant::where('email', $request->email)->first();
        $telephoneUnique = Etudiant::where('telephone', $request->telephone)->first();
        $usernameUnique = Etudiant::where('username', $request->username)->first();

        if($numCardUnique && $numCardUnique->id != $etudiant->id){
            return redirect()->back()->with(['error' => 'Ce numéro de carte étudiant existe déja !']);
        }
        if($emailUnique && $emailUnique->id != $etudiant->id){
            return redirect()->back()->with(['error' => 'Cet e-mail existe déja !']);
        }
        if($telephoneUnique && $telephoneUnique->id != $etudiant->id){
            return redirect()->back()->with(['error' => 'Ce numéro de téléphone existe déja !']);
        }
        if($usernameUnique && $usernameUnique->id != $etudiant->id){
            return redirect()->back()->with(['error' => 'Ce nom d\'utilisateur existe déja !']);
        }

        $data = $request->all();
        
        if($request->file('photo')){
            $photo = $request->file('photo');
            $extentionFichier = $photo->getClientOriginalExtension();
            $nomDuFichier = $request->numCarteEtud.'.'.$extentionFichier;
            $dossierContentFile = public_path('assets/images/etudiants/photos/');
            $uploadPhoto = $photo->move($dossierContentFile, $nomDuFichier);
            Storage::move($dossierContentFile, 'public/webinar/', $nomDuFichier);
            $data['photo'] = $nomDuFichier;
        }

        $timestamp = strtotime($request->datenais);
        $data['datenais'] = date('Y-m-d', $timestamp);

        if($request->access == "on"){
            $data['access'] = true;
        }

        if($request->roleID == null){
            $data['roleID'] = 1;
        }

        $etudiant->update($data);

        if($request->monProfil=="oui"){
            return redirect()->back()->with('success','Informations mises à jour !');
        }

        return redirect()->back()->with('success','Informations modifiées !');
    }  
        else {
            return redirect()->route('deconnexion');
        }    
    }


    public function desactivate($id)
    {
        $etudiant = Etudiant::find($id);
        if (!$etudiant) {
            redirect()->back()->with('error', 'Etudiant non trouvé !');
        }

        $etudiant->active = false;
        $etudiant->update();

        $paiements = Paiement::where([['etudiantID', $etudiant->id], ['active', true]])->get();

        if($paiements) {
            foreach ($paiements as $paiement){
                $paiement->active = false;
                $paiement->update();
            }
        }

        return redirect()->back()->with('success', 'Etudiant supprimé de la liste !');
    }

    function qrCodeInfos($id) {

        $etudiant = Etudiant::where('numCarteEtud', $id)->first();

        $menu = '';

        if($etudiant){

            $latestVersement = Paiement::where([['etudiantID', $etudiant->id], ['active', true]])->latest('created_at')->first();

            $montantRestant = $latestVersement->montantRestant;

            return view('public.student', compact('etudiant', 'menu', 'montantRestant'));
        }
        else {
            return view('public.badCode', compact('menu'));
        }
        
    }

    public function getUtilisateurs(Request $request){
        if(session()->has('utilisateur')){
            $utilisateur = Etudiant::find(session('utilisateur'));
            $paiements = Paiement::where([['active', true]])->orderBy('created_at', 'desc')->get();
            $etudiants = Etudiant::where([['active', true]])->orderBy('created_at', 'desc')->get();
            $fiveLast = Etudiant::where('active', true)->latest()->take(5)->get();
            $utilisateurs = Etudiant::where([['active', true], ['access', true]])->orderBy('created_at', 'desc')->get();
            $roles = Role::where([['active', true]])->orderBy('libelle', 'asc')->get();
            $menu = 'Utilisateurs';
            return view('management.users', compact('menu', 'utilisateur', 'roles', 'etudiants', 'paiements', 'fiveLast', 'utilisateurs'));
        }
        else {
            return redirect('deconnexion');
        }
    }
}
