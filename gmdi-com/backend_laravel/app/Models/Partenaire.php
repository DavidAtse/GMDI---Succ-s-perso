<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Partenaire extends Model {
    protected $fillable = ['nom','type','domaine','contact','date_debut','statut'];
    protected $casts    = ['date_debut'=>'date'];
}
