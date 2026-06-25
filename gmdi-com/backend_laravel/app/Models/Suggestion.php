<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Suggestion extends Model {
    protected $fillable = ['reference','objet','citoyen','description','date','statut'];
    protected $casts    = ['date'=>'date'];
}
