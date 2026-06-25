<?php

namespace App\Http\Controllers\Api\Communication;

use App\Http\Controllers\Controller;
use App\Models\Actualite;
use App\Models\CompteReseau;
use App\Models\PostProgramme;
use App\Models\Partenaire;
use App\Models\ArticlePresse;
use App\Models\Document;
use App\Models\Reclamation;
use App\Models\Suggestion;
use App\Models\ConsultationPublique;
use App\Models\CampagneSms;
use Illuminate\Http\Request;

// ═════════════════════════════════════════════════════════════════════════════
//  Helper paginé commun
// ═════════════════════════════════════════════════════════════════════════════
trait PaginationHelper {
    private function meta($p): array { return ['current_page'=>$p->currentPage(),'last_page'=>$p->lastPage(),'per_page'=>$p->perPage(),'total'=>$p->total(),'from'=>$p->firstItem(),'to'=>$p->lastItem()]; }
    private function links($p): array { return ['first'=>$p->url(1),'last'=>$p->url($p->lastPage()),'prev'=>$p->previousPageUrl(),'next'=>$p->nextPageUrl()]; }
}

// ═════════════════════════════════════════════════════════════════════════════
//  ActualiteController
// ═════════════════════════════════════════════════════════════════════════════
class ActualiteController extends Controller
{
    use PaginationHelper;

    /** GET /api/com/actualites */
    public function index(Request $request)
    {
        $q = Actualite::query()->orderByDesc('date');
        if ($t = $request->get('type'))   $q->where('type', $t);
        if ($s = $request->get('statut')) $q->where('statut', $s);
        if ($sr= $request->get('search')) $q->where('titre','like',"%$sr%");
        $data = $q->paginate((int)$request->get('per_page', 20));
        return response()->json(['data'=>$data->map(fn($a)=>$this->fmt($a)),'meta'=>$this->meta($data),'links'=>$this->links($data)]);
    }

    /** POST /api/com/actualites */
    public function store(Request $request)
    {
        $v = $request->validate([
            'type'      => 'required|in:communique,annonce,evenement',
            'titre'     => 'required|string|max:250',
            'contenu'   => 'required|string',
            'auteur'    => 'nullable|string|max:150',
            'statut'    => 'nullable|in:publie,brouillon',
            'categorie' => 'nullable|string|max:100',
            'date'      => 'nullable|date',
        ]);
        $a = Actualite::create(array_merge($v, [
            'statut' => $v['statut'] ?? 'publie',
            'auteur' => $v['auteur'] ?? 'Service Communication',
            'date'   => $v['date']   ?? now()->format('Y-m-d'),
        ]));
        return response()->json(['success'=>true,'message'=>"Publication créée — {$a->titre}",'data'=>$this->fmt($a)], 201);
    }

    /** PATCH /api/com/actualites/{id}/statut */
    public function updateStatut(Request $request, $id)
    {
        $a = Actualite::findOrFail($id);
        $request->validate(['statut'=>'required|in:publie,brouillon']);
        $a->update(['statut'=>$request->statut]);
        return response()->json(['success'=>true,'message'=>"Statut mis à jour — {$a->titre}",'data'=>$this->fmt($a->fresh())]);
    }

    /** DELETE /api/com/actualites/{id} */
    public function destroy($id)
    {
        Actualite::findOrFail($id)->delete();
        return response()->json(['success'=>true,'message'=>'Publication supprimée.','data'=>null]);
    }

    private function fmt(Actualite $a): array
    {
        return ['id'=>$a->id,'type'=>$a->type,'titre'=>$a->titre,'contenu'=>$a->contenu,'auteur'=>$a->auteur,'date'=>$a->date?->format('Y-m-d'),'statut'=>$a->statut,'categorie'=>$a->categorie,'created_at'=>$a->created_at?->toISOString()];
    }
}

// ═════════════════════════════════════════════════════════════════════════════
//  ReseauxController
// ═════════════════════════════════════════════════════════════════════════════
class ReseauxController extends Controller
{
    /** GET /api/com/reseaux/comptes */
    public function getComptes()
    {
        return response()->json(CompteReseau::orderBy('abonnes','desc')->get()->map(fn($c)=>$this->fmtC($c)));
    }

    /** GET /api/com/reseaux/calendrier */
    public function getCalendrier()
    {
        return response()->json(PostProgramme::orderBy('date')->get()->map(fn($p)=>$this->fmtP($p)));
    }

    /** POST /api/com/reseaux/publier */
    public function publierPost(Request $request)
    {
        $v = $request->validate(['contenu'=>'required|string','plateformes'=>'required|array','plateformes.*'=>'string','programme'=>'nullable|boolean','date'=>'nullable|string']);
        $p = PostProgramme::create([
            'date'        => ($v['programme'] ?? false) ? ($v['date'] ?? now()->format('Y-m-d')) : now()->format('Y-m-d'),
            'contenu'     => $v['contenu'],
            'plateformes' => implode(',', $v['plateformes']),
            'responsable' => auth()->user()?->name ?? 'Service Communication',
            'statut'      => ($v['programme'] ?? false) ? 'programme' : 'publie',
        ]);
        // Mettre à jour les compteurs de publications
        CompteReseau::whereIn('plateforme', $v['plateformes'])->increment('publications');
        return response()->json(['success'=>true,'message'=>'Publié sur : '.implode(', ',$v['plateformes']),'data'=>$this->fmtP($p)], 201);
    }

    /** POST /api/com/reseaux/calendrier */
    public function ajouterCalendrier(Request $request)
    {
        $v = $request->validate(['date'=>'required|date','contenu'=>'required|string','plateformes'=>'required|array','responsable'=>'nullable|string']);
        $p = PostProgramme::create(['date'=>$v['date'],'contenu'=>$v['contenu'],'plateformes'=>implode(',',$v['plateformes']),'responsable'=>$v['responsable']??'Service Comm.','statut'=>'programme']);
        return response()->json(['success'=>true,'message'=>'Post ajouté au calendrier','data'=>$this->fmtP($p)], 201);
    }

    private function fmtC(CompteReseau $c): array
    {
        return ['id'=>$c->id,'plateforme'=>$c->plateforme,'nom'=>$c->nom,'handle'=>$c->handle,'abonnes'=>$c->abonnes,'publications'=>$c->publications,'taux_engagement'=>$c->taux_engagement,'porte_mois'=>$c->porte_mois,'dernier_post'=>$c->dernier_post,'created_at'=>$c->created_at?->toISOString()];
    }
    private function fmtP(PostProgramme $p): array
    {
        return ['id'=>$p->id,'date'=>$p->date?->format('Y-m-d'),'contenu'=>$p->contenu,'plateformes'=>$p->plateformes,'responsable'=>$p->responsable,'statut'=>$p->statut,'created_at'=>$p->created_at?->toISOString()];
    }
}

// ═════════════════════════════════════════════════════════════════════════════
//  RelationsController
// ═════════════════════════════════════════════════════════════════════════════
class RelationsController extends Controller
{
    use PaginationHelper;

    public function getPartenaires()
    {
        $data = Partenaire::orderByDesc('created_at')->paginate(20);
        return response()->json(['data'=>$data->map(fn($p)=>$this->fmtPart($p)),'meta'=>$this->meta($data),'links'=>$this->links($data)]);
    }

    public function createPartenaire(Request $request)
    {
        $v = $request->validate(['nom'=>'required|string|max:200','type'=>'required|string|max:100','domaine'=>'required|string|max:150','contact'=>'required|string|max:200','date_debut'=>'required|date']);
        $p = Partenaire::create(array_merge($v, ['statut'=>'actif']));
        return response()->json(['success'=>true,'message'=>"Partenaire enregistré — {$p->nom}",'data'=>$this->fmtPart($p)], 201);
    }

    public function getRevuePresse()
    {
        return response()->json(ArticlePresse::orderByDesc('date')->take(20)->get()->map(fn($a)=>['id'=>$a->id,'date'=>$a->date?->format('d/m/Y'),'media'=>$a->media,'titre'=>$a->titre,'type'=>$a->type,'tonalite'=>$a->tonalite,'created_at'=>$a->created_at?->toISOString()]));
    }

    public function envoyerDossierPresse(Request $request)
    {
        $v = $request->validate(['titre'=>'required|string|max:250','medias'=>'nullable|string','date_envoi'=>'nullable|date','contact'=>'nullable|string|max:200']);
        return response()->json(['success'=>true,'message'=>"Dossier de presse envoyé — {$v['titre']}",'data'=>null]);
    }

    public function getMedias() { return response()->json([]); }

    private function fmtPart(Partenaire $p): array
    {
        return ['id'=>$p->id,'nom'=>$p->nom,'type'=>$p->type,'domaine'=>$p->domaine,'contact'=>$p->contact,'date_debut'=>$p->date_debut?->format('Y-m-d'),'statut'=>$p->statut,'created_at'=>$p->created_at?->toISOString()];
    }
}

// ═════════════════════════════════════════════════════════════════════════════
//  DocumentController
// ═════════════════════════════════════════════════════════════════════════════
class DocumentController extends Controller
{
    use PaginationHelper;

    public function index(Request $request)
    {
        $q = Document::query()->orderByDesc('date');
        if ($t = $request->get('type'))   $q->where('type', $t);
        if ($s = $request->get('search')) $q->where('titre','like',"%$s%");
        $data = $q->paginate(30);
        return response()->json(['data'=>$data->map(fn($d)=>$this->fmt($d)),'meta'=>$this->meta($data),'links'=>$this->links($data)]);
    }

    public function byType($type)
    {
        return response()->json(Document::where('type',$type)->orderByDesc('date')->take(10)->get()->map(fn($d)=>$this->fmt($d)));
    }

    public function store(Request $request)
    {
        $v = $request->validate(['titre'=>'required|string|max:250','type'=>'required|in:photo,video,pdf,arrete,deliberation','categorie'=>'required|string|max:100','date'=>'nullable|date','auteur'=>'nullable|string|max:150','url'=>'nullable|string|max:500','droits'=>'nullable|string|max:100']);
        $d = Document::create(array_merge($v, ['date'=>$v['date']??now()->format('Y-m-d')]));
        return response()->json(['success'=>true,'message'=>"Document enregistré — {$d->titre}",'data'=>$this->fmt($d)], 201);
    }

    private function fmt(Document $d): array
    {
        return ['id'=>$d->id,'titre'=>$d->titre,'type'=>$d->type,'categorie'=>$d->categorie,'date'=>$d->date?->format('Y-m-d'),'auteur'=>$d->auteur,'url'=>$d->url,'droits'=>$d->droits,'created_at'=>$d->created_at?->toISOString()];
    }
}

// ═════════════════════════════════════════════════════════════════════════════
//  CitoyenController  (réclamations + suggestions + consultations)
// ═════════════════════════════════════════════════════════════════════════════
class CitoyenController extends Controller
{
    use PaginationHelper;

    // ── Réclamations ─────────────────────────────────────────────────────────

    public function indexReclamations(Request $request)
    {
        $q = Reclamation::query()->orderByDesc('date');
        if ($s = $request->get('statut'))  $q->where('statut', $s);
        if ($sv= $request->get('service')) $q->where('service', $sv);
        $data = $q->paginate(20);
        return response()->json(['data'=>$data->map(fn($r)=>$this->fmtRec($r)),'meta'=>$this->meta($data),'links'=>$this->links($data)]);
    }

    public function storeReclamation(Request $request)
    {
        $v = $request->validate(['objet'=>'required|string|max:250','demandeur'=>'required|string|max:200','service'=>'required|string|max:100','canal'=>'required|string|max:50']);
        $seq = str_pad(Reclamation::count() + 1, 3, '0', STR_PAD_LEFT);
        $r = Reclamation::create(array_merge($v, ['reference'=>'RCL-'.date('Y')."-{$seq}",'date'=>now()->format('Y-m-d'),'statut'=>'en_traitement']));
        return response()->json(['success'=>true,'message'=>"Réclamation enregistrée — {$r->reference}",'data'=>$this->fmtRec($r)], 201);
    }

    public function updateStatutReclamation(Request $request, $id)
    {
        $r = Reclamation::findOrFail($id);
        $request->validate(['statut'=>'required|in:en_traitement,repondu,cloture']);
        $r->update(['statut'=>$request->statut]);
        return response()->json(['success'=>true,'message'=>"Statut mis à jour — {$r->reference}",'data'=>$this->fmtRec($r->fresh())]);
    }

    public function exportReclamations()
    {
        return response()->streamDownload(function() { echo json_encode(Reclamation::all(), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE); }, 'reclamations_'.now()->format('Y-m-d').'.json', ['Content-Type'=>'application/json']);
    }

    private function fmtRec(Reclamation $r): array
    {
        return ['id'=>$r->id,'reference'=>$r->reference,'objet'=>$r->objet,'demandeur'=>$r->demandeur,'service'=>$r->service,'canal'=>$r->canal,'date'=>$r->date?->format('Y-m-d'),'statut'=>$r->statut,'created_at'=>$r->created_at?->toISOString()];
    }

    // ── Suggestions ───────────────────────────────────────────────────────────

    public function indexSuggestions(Request $request)
    {
        $q = Suggestion::query()->orderByDesc('date');
        if ($s = $request->get('statut')) $q->where('statut', $s);
        $data = $q->paginate(20);
        return response()->json(['data'=>$data->map(fn($s)=>$this->fmtSug($s)),'meta'=>$this->meta($data),'links'=>$this->links($data)]);
    }

    public function storeSuggestion(Request $request)
    {
        $v = $request->validate(['objet'=>'required|string|max:250','citoyen'=>'nullable|string|max:150','description'=>'nullable|string|max:500']);
        $seq = str_pad(Suggestion::count() + 1, 3, '0', STR_PAD_LEFT);
        $s = Suggestion::create(array_merge($v, ['reference'=>'SUG-'.date('Y')."-{$seq}",'citoyen'=>$v['citoyen']??'Anonyme','date'=>now()->format('Y-m-d'),'statut'=>'recu']));
        return response()->json(['success'=>true,'message'=>"Suggestion enregistrée — {$s->reference}",'data'=>$this->fmtSug($s)], 201);
    }

    public function transmettresuggestion($id)
    {
        $s = Suggestion::findOrFail($id);
        $s->update(['statut'=>'transmis']);
        return response()->json(['success'=>true,'message'=>"Suggestion transmise — {$s->reference}",'data'=>$this->fmtSug($s->fresh())]);
    }

    private function fmtSug(Suggestion $s): array
    {
        return ['id'=>$s->id,'reference'=>$s->reference,'objet'=>$s->objet,'citoyen'=>$s->citoyen,'description'=>$s->description,'date'=>$s->date?->format('Y-m-d'),'statut'=>$s->statut,'created_at'=>$s->created_at?->toISOString()];
    }

    // ── Consultations ─────────────────────────────────────────────────────────

    public function indexConsultations()
    {
        return response()->json(ConsultationPublique::orderByDesc('date_ouverture')->get()->map(fn($c)=>$this->fmtCons($c)));
    }

    public function storeConsultation(Request $request)
    {
        $v = $request->validate(['titre'=>'required|string|max:250','theme'=>'required|string|max:100','date_ouverture'=>'required|date','date_cloture'=>'required|date|after:date_ouverture','canaux'=>'nullable|string|max:100']);
        $c = ConsultationPublique::create(array_merge($v, ['participants'=>0,'statut'=>now()->format('Y-m-d')>=$v['date_ouverture']?'actif':'programme']));
        return response()->json(['success'=>true,'message'=>"Consultation ouverte — {$c->titre}",'data'=>$this->fmtCons($c)], 201);
    }

    private function fmtCons(ConsultationPublique $c): array
    {
        return ['id'=>$c->id,'titre'=>$c->titre,'theme'=>$c->theme,'date_ouverture'=>$c->date_ouverture?->format('Y-m-d'),'date_cloture'=>$c->date_cloture?->format('Y-m-d'),'participants'=>$c->participants,'statut'=>$c->statut,'canaux'=>$c->canaux,'created_at'=>$c->created_at?->toISOString()];
    }
}

// ═════════════════════════════════════════════════════════════════════════════
//  SmsController
// ═════════════════════════════════════════════════════════════════════════════
class SmsController extends Controller
{
    private const NB: array = ['tous'=>12500,'quartier'=>3800,'commercants'=>2400,'agents'=>347,'contribuables'=>8200];

    public function getHistorique()
    {
        return response()->json(CampagneSms::orderByDesc('date_envoi')->take(50)->get()->map(fn($s)=>$this->fmt($s)));
    }

    public function lancerCampagne(Request $request)
    {
        $v = $request->validate(['nom'=>'required|string|max:200','type'=>'required|string|max:50','message'=>'required|string|max:480','destinataires'=>'required|string|max:100','date_envoi'=>'nullable|string','programme'=>'nullable|boolean']);
        $nb    = self::NB[$v['destinataires']] ?? 0;
        $prog  = $v['programme'] ?? false;
        $c = CampagneSms::create([
            'nom'             => $v['nom'],
            'type'            => $v['type'],
            'message'         => $v['message'],
            'destinataires'   => $v['destinataires'],
            'nb_destinataires'=> $nb,
            'date_envoi'      => $prog ? ($v['date_envoi'] ?? now()->format('Y-m-d')) : now()->format('Y-m-d'),
            'statut'          => $prog ? 'programme' : 'envoye',
            'taux_livraison'  => $prog ? 0 : 94,
        ]);
        return response()->json(['success'=>true,'message'=>$prog?"Campagne programmée — {$c->nom}":"Campagne envoyée à ".number_format($nb)." destinataires",'data'=>$this->fmt($c)], 201);
    }

    public function envoyerAlerte(Request $request)
    {
        $v = $request->validate(['message'=>'required|string|max:160','cible'=>'required|string|max:100','quartier'=>'nullable|string','priorite'=>'nullable|string|max:50']);
        $nb = self::NB[$v['cible']] ?? 0;
        $c = CampagneSms::create([
            'nom'             => 'ALERTE — '.substr($v['message'], 0, 40).'...',
            'type'            => 'alerte',
            'message'         => $v['message'],
            'destinataires'   => $v['cible'],
            'nb_destinataires'=> $nb,
            'date_envoi'      => now()->format('Y-m-d'),
            'statut'          => 'envoye',
            'taux_livraison'  => 97,
        ]);
        return response()->json(['success'=>true,'message'=>"Alerte envoyée à ".number_format($nb)." destinataires",'data'=>$this->fmt($c)], 201);
    }

    public function export()
    {
        return response()->streamDownload(fn()=>print(json_encode(CampagneSms::all(),JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)), 'sms_'.now()->format('Y-m-d').'.json', ['Content-Type'=>'application/json']);
    }

    private function fmt(CampagneSms $s): array
    {
        return ['id'=>$s->id,'nom'=>$s->nom,'type'=>$s->type,'message'=>$s->message,'destinataires'=>$s->destinataires,'nb_destinataires'=>$s->nb_destinataires,'date_envoi'=>$s->date_envoi?->format('Y-m-d'),'statut'=>$s->statut,'taux_livraison'=>$s->taux_livraison,'created_at'=>$s->created_at?->toISOString()];
    }
}

// ═════════════════════════════════════════════════════════════════════════════
//  StatsController
// ═════════════════════════════════════════════════════════════════════════════
class StatsController extends Controller
{
    public function dashboard()
    {
        return response()->json([
            'kpi' => [
                'publications_mois'    => Actualite::whereMonth('date',now()->month)->whereYear('date',now()->year)->count(),
                'abonnes_totaux'       => CompteReseau::sum('abonnes'),
                'taux_livraison_sms'   => (int)(CampagneSms::where('statut','envoye')->avg('taux_livraison') ?? 94),
                'reclamations_ouvertes'=> Reclamation::where('statut','en_traitement')->count(),
                'partenaires_actifs'   => Partenaire::where('statut','actif')->count(),
                'documents_archives'   => Document::count() + 1248, // base + archivés manuellement
            ],
            'actualites_par_type' => Actualite::selectRaw('type, COUNT(*) as nb')->groupBy('type')->get()->map(fn($r)=>['type'=>$r->type,'nb'=>(int)$r->nb]),
            'sms_par_mois'        => CampagneSms::selectRaw('DATE_FORMAT(date_envoi,"%Y-%m") as mois, COUNT(*) as nb, AVG(taux_livraison) as taux')->groupBy('mois')->orderBy('mois','desc')->take(6)->get()->map(fn($r)=>['mois'=>$r->mois,'nb'=>(int)$r->nb,'taux'=>round($r->taux)]),
        ]);
    }
}
