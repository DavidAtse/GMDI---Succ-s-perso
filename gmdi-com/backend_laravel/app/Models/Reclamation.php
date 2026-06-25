<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Reclamation extends Model {
    protected $fillable = ['reference','objet','demandeur','service','canal','date','statut'];
    protected $casts    = ['date'=>'date'];
}
