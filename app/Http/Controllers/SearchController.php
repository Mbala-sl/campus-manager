<?php

namespace App\Http\Controllers;

use App\Models\AnneeAcademique;
use App\Models\Filiere;
use App\Models\Inscription;
use App\Models\Student;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __invoke(Request $request)
    {
        $q = trim($request->input('q', ''));

        if (strlen($q) < 2) {
            return response()->json(['results' => [], 'query' => $q]);
        }

        $results = [];

        // ── Étudiants ─────────────────────────────────────
        $students = Student::with('filiere')
            ->where(fn($sq) =>
                $sq->where('nom',       'like', "%$q%")
                   ->orWhere('prenom',  'like', "%$q%")
                   ->orWhere('matricule','like', "%$q%")
                   ->orWhere('email',   'like', "%$q%")
            )
            ->orderBy('nom')
            ->limit(6)
            ->get();

        if ($students->isNotEmpty()) {
            $results[] = [
                'group' => 'Étudiants',
                'items' => $students->map(fn($s) => [
                    'label'    => $s->nom_complet,
                    'sub'      => $s->matricule . ' · ' . ($s->filiere?->nom ?? '—') . ' ' . $s->niveau,
                    'url'      => route('students.index', ['q' => $s->matricule]),
                    'badge'    => $s->statut_label,
                    'badge_bg' => $s->statut_colors[0],
                    'badge_fg' => $s->statut_colors[1],
                    'type'     => 'student',
                    'initiale' => $s->initiale,
                ])->values(),
            ];
        }

        // ── Filières ──────────────────────────────────────
        $filieres = Filiere::withCount('students')
            ->where(fn($fq) =>
                $fq->where('nom',  'like', "%$q%")
                   ->orWhere('code','like', "%$q%")
            )
            ->limit(4)
            ->get();

        if ($filieres->isNotEmpty()) {
            $results[] = [
                'group' => 'Filières',
                'items' => $filieres->map(fn($f) => [
                    'label'    => $f->nom,
                    'sub'      => 'Code : ' . $f->code . ' · ' . number_format($f->students_count) . ' étudiant(s)',
                    'url'      => route('filieres.index', ['q' => $f->nom]),
                    'badge'    => $f->actif ? 'Active' : 'Inactive',
                    'badge_bg' => $f->actif ? '#DCFCE7' : '#FEE2E2',
                    'badge_fg' => $f->actif ? '#15803D' : '#DC2626',
                    'type'     => 'filiere',
                ])->values(),
            ];
        }

        // ── Inscriptions ──────────────────────────────────
        $inscriptions = Inscription::with(['student', 'filiere'])
            ->whereHas('student', fn($sq) =>
                $sq->where('nom',    'like', "%$q%")
                   ->orWhere('prenom','like', "%$q%")
                   ->orWhere('matricule','like',"%$q%")
            )
            ->limit(4)
            ->get();

        if ($inscriptions->isNotEmpty()) {
            $results[] = [
                'group' => 'Inscriptions',
                'items' => $inscriptions->map(fn($i) => [
                    'label'    => $i->student?->nom_complet ?? '—',
                    'sub'      => $i->filiere?->nom . ' · ' . $i->niveau . ' · ' . $i->annee_academique,
                    'url'      => route('inscriptions.index', ['q' => $i->student?->matricule]),
                    'badge'    => $i->statut_label,
                    'badge_bg' => $i->statut_colors[0],
                    'badge_fg' => $i->statut_colors[1],
                    'type'     => 'inscription',
                ])->values(),
            ];
        }

        // ── Années académiques ────────────────────────────
        $annees = AnneeAcademique::where('label', 'like', "%$q%")
            ->orWhere('description', 'like', "%$q%")
            ->limit(3)
            ->get();

        if ($annees->isNotEmpty()) {
            $results[] = [
                'group' => 'Années académiques',
                'items' => $annees->map(fn($a) => [
                    'label'    => $a->label,
                    'sub'      => $a->date_debut?->format('d/m/Y') . ' — ' . $a->date_fin?->format('d/m/Y'),
                    'url'      => route('annees.index'),
                    'badge'    => $a->statut_label,
                    'badge_bg' => $a->statut_colors[0],
                    'badge_fg' => $a->statut_colors[1],
                    'type'     => 'annee',
                ])->values(),
            ];
        }

        // ── Navigation / Pages ────────────────────────────
        $pages = [
            ['label' => 'Tableau de bord',       'sub' => 'Statistiques et aperçu général',   'url' => route('dashboard'),           'icon' => 'dashboard'],
            ['label' => 'Gestion des étudiants',  'sub' => 'Liste, ajout, modification CRUD',  'url' => route('students.index'),      'icon' => 'students'],
            ['label' => 'Filières',               'sub' => 'Programmes et codes filière',       'url' => route('filieres.index'),      'icon' => 'filieres'],
            ['label' => 'Inscriptions',           'sub' => 'Dossiers d\'inscription',           'url' => route('inscriptions.index'),  'icon' => 'inscriptions'],
            ['label' => 'Années académiques',     'sub' => 'Périodes et années scolaires',      'url' => route('annees.index'),        'icon' => 'annees'],
        ];

        $ql = strtolower($q);
        $matched = array_values(array_filter($pages, fn($p) =>
            str_contains(strtolower($p['label']), $ql) ||
            str_contains(strtolower($p['sub']),   $ql)
        ));

        if ($matched) {
            $results[] = [
                'group' => 'Pages',
                'items' => array_map(fn($p) => array_merge($p, ['badge' => null, 'type' => 'page']), $matched),
            ];
        }

        return response()->json(['results' => $results, 'query' => $q]);
    }
}
