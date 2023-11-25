<?php

namespace App\Http\Controllers;

use App\Models\Etudiant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    function index(){
        $menu = 'Connexion';
        return view('login', compact('menu'));
    }

    function connect(Request $request)
    {
        $validator = validator($request->all(),
            [
                'email' => 'email|required',
                'passwd' => 'required|min:6',
            ],
            [
                'email.required' => 'Vous devez fournir votre e-mail.',
                'passwd.required' => 'Vous devez indiquer votre mot de passe.',
                'passwd.min' => 'le mot de passe doit contenir au moins 6 caractères'
            ],
        );

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $utilisateur = Etudiant::where([['email', $request->email], ['access', true], ['active', true]])->first();
        
        if($utilisateur){
            if(Hash::check($request->passwd, $utilisateur->passwd)){
                $request->session()->put('utilisateur', $utilisateur->id);
                return redirect()->route('home');
            }
            else{
                return redirect()->back()->with(['error' => 'Mot de passe incorrect !']);
            }
        }
        else{
            return redirect()->back()->with(['error' => 'Accès refusé !']);
        }
    }

    function disconnect(){
        if(session()->has('utilisateur')){ 
            session()->pull('utilisateur');
        }
        return redirect()->route('login');
    }
}
