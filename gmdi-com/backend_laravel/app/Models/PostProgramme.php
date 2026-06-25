<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class PostProgramme extends Model {
    protected $fillable = ['date','contenu','plateformes','responsable','statut'];
    protected $casts    = ['date'=>'date'];
}
