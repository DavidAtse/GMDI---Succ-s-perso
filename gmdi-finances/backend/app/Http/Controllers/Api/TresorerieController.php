<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MouvementBanque;
use App\Models\MouvementCaisse;

class TresorerieController extends Controller
{
    public function mouvementsCaisse()
    {
        return response()->json(MouvementCaisse::orderByDesc('date')->get());
    }

    public function mouvementsBanque()
    {
        return response()->json(MouvementBanque::orderByDesc('date')->get());
    }
}
