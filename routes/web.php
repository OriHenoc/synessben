<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\StudentController;

//
Route::get('/connexion', [LoginController::class, 'index'])->name('login')->middleware('isAlreadyLogged');
Route::post('/connect', [LoginController::class, 'connect'])->name('connect')->middleware('isAlreadyLogged');
Route::get('/deconnexion', [LoginController::class, 'disconnect'])->name('deconnexion')->middleware('isLogged');;
//
Route::get('/', [HomeController::class, 'index'])->name('home')->middleware('isLogged');
Route::get('/profil', [HomeController::class, 'profile'])->name('profil')->middleware('isLogged');
Route::post('/modifier-mot-de-passe', [HomeController::class, 'updatePassword'])->name('modifierMotDePasse')->middleware('isLogged');
Route::post('/changePP', [HomeController::class, 'changePP'])->name('changePP')->middleware('isLogged');
//
Route::get('/etudiants', [StudentController::class, 'index'])->name('students')->middleware('isLogged');
Route::post('/inscrire', [StudentController::class, 'createStudent'])->name('inscrire')->middleware('isLogged');
Route::get('/etudiant/{id}', [StudentController::class, 'getDetails'])->name('infosEtudiant')->middleware('isLogged');
Route::get('/modifier-infos-etudiant/{id}', [StudentController::class, 'getForUpdate'])->name('getEtudiant')->middleware('isLogged');
Route::post('/modifierEtudiant', [StudentController::class, 'updateUser'])->name('updateEtudiant')->middleware('isLogged');
Route::post('/supprimerEtudiant/{id}', [StudentController::class, 'desactivate'])->name('supprimerEtudiant')->middleware('isLogged');
Route::get('/qrcode/{id}', [StudentController::class, 'qrCodeInfos'])->name('qrcode');
//
Route::get('/paiements', [PaymentController::class, 'index'])->name('payments')->middleware('isLogged');
Route::post('/payer', [PaymentController::class, 'createPayment'])->name('payer')->middleware('isLogged');
Route::get('/ajouter-versement/{id}', [PaymentController::class, 'getPaymentAjoutDetails'])->name('infosVersementAjout')->middleware('isLogged');
Route::get('/versements-etudiant/{id}', [PaymentController::class, 'getAllEtudiantPayments'])->name('getAllEtudiantPayments')->middleware('isLogged');
Route::get('/recu-versements-etudiant/{id}', [PaymentController::class, 'getAllEtudiantPaymentsJson'])->name('getAllEtudiantPaymentsJson')->middleware('isLogged');
Route::post('/supprimerPaiements/{id}', [PaymentController::class, 'desactivateAll'])->name('supprimerPaiements')->middleware('isLogged');
Route::post('/envoyer-recu', [PaymentController::class, 'sendInvoice'])->name('envoyerRecu')->middleware('isLogged');
Route::get('/qrcode-etudiant/{id}', [PaymentController::class, 'getQrCode'])->name('voirQrCode')->middleware('isLogged');
Route::post('/envoyer-qr-code', [PaymentController::class, 'sendQrCode'])->name('envoyerQrCode')->middleware('isLogged');