<?php

namespace App\Models;

use App\Models\Role;
use App\Models\Paiement;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Etudiant extends Model
{
    use HasFactory;

    public $fillable = [
        'photo',
        'nom',
        'prenoms',
        'datenais',
        'niveau',
        'numCarteEtud',
        'email',
        'telephone',
        'access',
        'username',
        'passwd',
        'roleID'
    ];

    public function role(){
        return $this->belongsTo(Role::class, 'roleID', 'id');
    }

    public function paiements() 
    {
        return $this->hasMany(Paiement::class, 'etudiantID');
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'passwd',
    ];
}