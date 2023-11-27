<?php

namespace App\Models;

use App\Models\Etudiant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Paiement extends Model
{
    use HasFactory;

    public $fillable = [
        'montantApayer',
        'montantPaye',
        'datePaiement',
        'commentaire',
        'montantRestant',
        'recu',
        'qrCode',
        'etudiantID',
        'createdBy',
        'updatedBy'
    ];

    public function etudiant(){
        return $this->belongsTo(Etudiant::class, 'etudiantID', 'id');
    }

    public function createdPar(){
        return $this->belongsTo(Etudiant::class, 'createdBy', 'id');
    }

    public function updatedPar(){
        return $this->belongsTo(Etudiant::class, 'updatedBy', 'id');
    }

}