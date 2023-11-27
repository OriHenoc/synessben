<?php

namespace App\Http\Controllers;

use TCPDF;
use App\Models\Role;
use App\Models\Etudiant;
use App\Models\Paiement;
use Spatie\PdfToImage\Pdf;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

use function PHPUnit\Framework\isEmpty;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PaymentController extends Controller
{
    public function index(){
        if(session()->has('utilisateur')){
            $utilisateur = Etudiant::find(session('utilisateur'));
            $paiements = Paiement::where('active', true)->orderBy('created_at', 'desc')->get();

            // Requete pour obtenir le dernier montant restant par étudiant
            $req = Paiement::where('active', true)->select('etudiantID', DB::raw('MIN(montantRestant) as minMontantRestant'))
            ->groupBy('etudiantID');

            // Joindre la requete avec la table originale pour obtenir les enregistrements voulus
            $paiementsEtudiants = Paiement::joinSub($req, 'min_paiements', function ($join) {
                $join->on('paiements.etudiantID', '=', 'min_paiements.etudiantID')
                    ->on('paiements.montantRestant', '=', 'min_paiements.minMontantRestant');
            })
            ->select('paiements.*')
            ->get();

            $etudiants = Etudiant::where([['active', true]])->orderBy('created_at', 'desc')->get();
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
                ->whereHas('role', function ($query) {
                    $query->where('libelle', 'ETUDIANT');
                })
                ->orderBy('created_at', 'desc')
                ->get();

            $fiveLast = Etudiant::where('active', true)->latest()->take(5)->get();
            $utilisateurs = Etudiant::where([['active', true], ['access', true]])->orderBy('created_at', 'desc')->get();
            $roles = Role::where([['active', true]])->orderBy('libelle', 'desc')->get();
            $menu = 'Paiements';
            return view('management.payments', compact('menu', 'utilisateur', 'etudiants', 'etudiantsWithoutPaiements', 'paiements', 'paiementsEtudiants', 'utilisateurs', 'roles', 'fiveLast'));
        }
        else {
            return redirect('deconnexion');
        }
    }

    public function getAllEtudiantPayments($id){
        if(session()->has('utilisateur')){
            $utilisateur = Etudiant::find(session('utilisateur'));
            $paiements = Paiement::where('active', true)->orderBy('created_at', 'desc')->get();
            $versements = Paiement::where([['etudiantID', $id], ['active', true]])->orderBy('created_at', 'desc')->get();
            if($versements->isEmpty()){
                return redirect()->back();
            }
            $etudiants = Etudiant::where([['active', true]])->orderBy('created_at', 'desc')->get();
            $fiveLast = Etudiant::where('active', true)->latest()->take(5)->get();
            $utilisateurs = Etudiant::where([['active', true], ['access', true]])->orderBy('created_at', 'desc')->get();
            $roles = Role::where([['active', true]])->orderBy('libelle', 'desc')->get();
            $menu = 'Versements de <b>'.$versements[0]->etudiant->nom.' '.$versements[0]->etudiant->prenoms.'</b>';
            return view('management.studentVersements', compact('menu', 'utilisateur', 'etudiants', 'paiements', 'versements', 'utilisateurs', 'roles', 'fiveLast'));
        }
        else {
            return redirect('deconnexion');
        }
    }

    public function getAllEtudiantPaymentsJson($id){
        if(session()->has('utilisateur')){
            $versements = Paiement::where([['etudiantID', $id], ['active', true]])->orderBy('created_at', 'desc')->get();
            $latestVersement = Paiement::where([['etudiantID', $id], ['active', true]])->latest('created_at')->first();
            if($versements->isEmpty()){
                return redirect()->back();
            }

            $image = '';

            if($versements){
                $image = $versements[0]->qrCode;
            }

            return response()->json([
                'versements' => $versements,
                'nom' => $latestVersement->etudiant->nom,
                'prenoms' => $latestVersement->etudiant->prenoms,
                'carte' => $latestVersement->etudiant->numCarteEtud,
                'statut' => $latestVersement->montantRestant == 0 ? 'Soldé' : 'Acompté',
                'image' => $image
            ]);
        }
        else {
            return redirect('deconnexion');
        }
    }

    public function createPayment(Request $request)
    {
        if(session()->has('utilisateur')){
        
        $utilisateur = Etudiant::find(session('utilisateur'));

        $validator = validator($request->all(),
            [
                'etudiantID' => 'required',
                'montantApayer' => 'required',
                'montantPaye' => 'required',
                'datePaiement' => 'required'
            ],
            [
                'etudiantID.required' => 'Sélectionnez l\'étudiant concerné',
                'montantApayer.required' => 'Le montant à payer est requis',
                'datePaiement.required' => 'La date de paiement est requise',
                'montantPaye.required' => 'Le montant payé est requis'
            ]
        );

       if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();
        
        $montantRest = (int)$request->montantApayer - (int)$request->montantPaye;

        if($montantRest > 0){
            $data['montantRestant'] = $montantRest;
        }
        else{
            $data['montantRestant'] = 0;
        }

        $timestamp = strtotime($request->datePaiement);
        $data['datePaiement'] = date('Y-m-d', $timestamp);

        $data['createdBy'] = $utilisateur->id;
        $data['updatedBy'] = $utilisateur->id;

        $etudiant = Etudiant::find($request->etudiantID);

        $paiement = Paiement::create($data);

        //ENVOYER EMAIL A LETUDIANT ET AUX ADMINS
        /*if($etudiant->access){
            Mail::send([], [], function (Message $message) use ($etudiant, $paiement) {
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
                        $etudiant.
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
        }*/

        return redirect()->route('payments')->with('success', 'Paiement enregistré avec succès !');
        }
        else {
            return redirect('deconnexion');
        }
    }

    public function getPaymentAjoutDetails($id)
    {
        if(session()->has('utilisateur')){
            $utilisateur = Etudiant::find(session('utilisateur'));
            $paiement = Paiement::find($id);

            if (!$paiement) {
                return response()->json(['error' => 'Paiement non trouvé'], 404);
            }

            return response()->json([
                'etudiantID' => $paiement->etudiantID,
                'montantApayer' => $paiement->montantRestant
            ]);
        }
        else {
            return redirect('deconnexion');
        }
    }

    public function desactivateAll($id)
    {
        if(session()->has('utilisateur')){
            $paiements = Paiement::where('etudiantID', $id)->get();

            if (!$paiements) {
                redirect()->back()->with('error', 'Paiements non trouvés !');
            }

            foreach($paiements as $paiement){
                $paiement->active = false;
                $paiement->update();
            }

            return redirect()->back()->with('success', 'Paiements supprimés de la liste !');
        }
        else {
            return redirect('deconnexion');
        }

    }

    function sendInvoice(Request $request){
        if(session()->has('utilisateur')){
        
        $etudiant = Etudiant::find($request->etudiantID);

        if ($request->has('send_receipt')) {
            // Use html2canvas to capture the content as an image
            $imageData = $request->input('image_data');  // Replace 'image_data' with the actual field name
    
            $img = str_replace('data:image/png;base64,', '', $imageData);
        $img = str_replace(' ', '+', $img);
        $imgData = base64_decode($img);
        $imgPath = public_path('assets/images/etudiants/recus/') . $etudiant->numCarteEtud . '.png';
        file_put_contents($imgPath, $imgData);

         // Convert the image to PDF using TCPDF
            $pdfPath = public_path('assets/images/etudiants/recus/') . $etudiant->numCarteEtud . '.pdf';

            $pdf = new TCPDF();
            $pdf->SetPageOrientation('L');
            $pdf->AddPage();
            $pdf->Image($imgPath); // Adjust the parameters as needed
            $pdf->Output($pdfPath, 'F');

            // Send the PDF via email
            $recipientEmail = $etudiant->email;  // Replace with the actual recipient's email
            $subject = 'Votre reçu de paiement SYNESS-BEN';

            //dd($recipientEmail);
    
            Mail::send([], [], function ($message) use ($pdfPath, $recipientEmail, $subject) {
                $message->to($recipientEmail)
                    ->subject($subject)
                    ->attach($pdfPath);
            });

            return redirect()->back()->with('success', 'Reçu envoyé avec succès !');
        }
            
            return redirect()->back()->with('error', 'Reçu non envoyé !');
        }
        else {
            return redirect('deconnexion');
        }
    }

    function getQrCode($id){
        if(session()->has('utilisateur')){
            $etudiant = Etudiant::find($id);
            $versements = Paiement::where([['etudiantID', $id], ['active', true]])->orderBy('created_at', 'desc')->get();
            //$latestVersement = Paiement::where([['etudiantID', $id], ['active', true]])->latest('created_at')->first();
            if($versements->isEmpty()){
                return redirect()->back();
            }

            $paiements = Paiement::where([['etudiantID', $id], ['active', true]])->get();
            
            //if(!$paiements[0]->qrCode) {

                $data = 'https://synessben.committeam.com/qrcode/'.$etudiant->numCarteEtud;

                $qrPath = public_path('assets/images/etudiants/qrcode/') . $etudiant->numCarteEtud . '.png';
                
                QrCode::format('png')->size(200)->color(0, 0, 0)->generate($data, $qrPath);

                foreach($paiements as $paiement){
                    $paiement->qrCode = $etudiant->numCarteEtud . '.png';
                    $paiement->update();
                }

            //}

        return response()->json([
            'image' => $paiements[0]->qrCode,
        ]);

        }
        else {
            return redirect('deconnexion');
        }
    }

    function sendQrCode(Request $request){
        if(session()->has('utilisateur')){

        $etudiant = Etudiant::find($request->etudiantID);

        $paiement = Paiement::where('etudiantID', $etudiant->id)->first();

        if ($request->has('send_receipt') && $paiement->qrCode != null) {

            $qrPath = public_path('assets/images/etudiants/qrcode/') . $paiement->qrCode;

            //$pdfPath = public_path('assets/images/etudiants/qrcode/') . $etudiant->numCarteEtud . '.pdf';
/*
            $pdf = new TCPDF();
            $pdf->SetPageOrientation('L');
            $pdf->AddPage();
            $pdf->Image($qrPath);
            $pdf->Output($pdfPath, 'F');*/

            $recipientEmail = $etudiant->email;
            $subject = 'Votre QR CODE de vérification SYNESS-BEN';

            Mail::send([], [], function ($message) use ($qrPath, $recipientEmail, $subject) {
                $message->to($recipientEmail)
                    ->subject($subject)
                    ->attach($qrPath);
            });
            
            //unlink($pdfPath);

            return redirect()->back()->with('success', 'QR CODE envoyé avec succès !');

        }

        return redirect()->back()->with('error', 'QR CODE non envoyé !');

            
        }
        else {
            return redirect('deconnexion');
        }
    }
}