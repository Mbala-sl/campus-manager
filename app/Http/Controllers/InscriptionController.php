<?php

namespace App\Http\Controllers;

use App\Models\Filiere;
use App\Models\Inscription;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class InscriptionController extends Controller
{
    public function index(Request $request)
    {
        $query = Inscription::with(['student', 'filiere'])->latest();

        if ($request->filled('statut') && $request->statut !== 'toutes') {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('annee') && $request->annee !== 'toutes') {
            $query->where('annee_academique', $request->annee);
        }

        if ($request->filled('q')) {
            $q = $request->q;
            $query->whereHas('student', fn($sq) =>
                $sq->where('prenom', 'like', "%$q%")
                   ->orWhere('nom', 'like', "%$q%")
                   ->orWhere('matricule', 'like', "%$q%")
            );
        }

        $inscriptions = $query->paginate(12)->withQueryString();
        $students     = Student::orderBy('nom')->get(['id', 'prenom', 'nom', 'matricule', 'filiere_id', 'niveau']);
        $filieres     = Filiere::where('actif', true)->orderBy('nom')->get(['id', 'nom', 'code']);
        $annees       = Inscription::distinct()->pluck('annee_academique')->sortDesc()->values();

        $kpis = [
            'total'      => Inscription::count(),
            'en_attente' => Inscription::where('statut', 'en_attente')->count(),
            'validees'   => Inscription::where('statut', 'validee')->count(),
            'refusees'   => Inscription::where('statut', 'refusee')->count(),
        ];

        return view('inscriptions.index', compact('inscriptions', 'students', 'filieres', 'annees', 'kpis'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'student_id'       => ['required', 'exists:students,id'],
            'filiere_id'       => ['required', 'exists:filieres,id'],
            'niveau'           => ['required', 'in:L1,L2,L3,M1,M2'],
            'annee_academique' => ['required', 'string', 'max:20'],
            'statut'           => ['required', 'in:en_attente,validee,refusee'],
            'date_inscription' => ['nullable', 'date'],
            'notes'            => ['nullable', 'string', 'max:500'],
        ]);

        $exists = Inscription::where('student_id', $data['student_id'])
            ->where('filiere_id', $data['filiere_id'])
            ->where('annee_academique', $data['annee_academique'])
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Cet étudiant est déjà inscrit dans cette filière pour cette année.',
            ], 422);
        }

        $data['date_inscription'] = $data['date_inscription'] ?? now()->format('Y-m-d');
        $inscription = Inscription::create($data);
        $inscription->load(['student', 'filiere']);

        return response()->json([
            'success'     => true,
            'message'     => "L'inscription de {$inscription->student->nom_complet} a été créée.",
            'inscription' => $this->format($inscription),
            'kpis'        => $this->getKpis(),
        ]);
    }

    public function update(Request $request, Inscription $inscription)
    {
        $data = $request->validate([
            'student_id'       => ['required', 'exists:students,id'],
            'filiere_id'       => ['required', 'exists:filieres,id'],
            'niveau'           => ['required', 'in:L1,L2,L3,M1,M2'],
            'annee_academique' => ['required', 'string', 'max:20'],
            'statut'           => ['required', 'in:en_attente,validee,refusee'],
            'date_inscription' => ['nullable', 'date'],
            'notes'            => ['nullable', 'string', 'max:500'],
        ]);

        $inscription->update($data);
        $inscription->load(['student', 'filiere']);

        return response()->json([
            'success'     => true,
            'message'     => "L'inscription a été mise à jour.",
            'inscription' => $this->format($inscription),
            'kpis'        => $this->getKpis(),
        ]);
    }

    public function destroy(Inscription $inscription)
    {
        $name = $inscription->student?->nom_complet ?? 'Étudiant';
        $inscription->delete();

        return response()->json([
            'success' => true,
            'message' => "L'inscription de {$name} a été supprimée.",
            'kpis'    => $this->getKpis(),
        ]);
    }

    private function format(Inscription $i): array
    {
        return [
            'id'               => $i->id,
            'student_id'       => $i->student_id,
            'student_nom'      => $i->student?->nom_complet ?? '—',
            'student_initiale' => $i->student?->initiale ?? '?',
            'student_matricule'=> $i->student?->matricule ?? '',
            'filiere_id'       => $i->filiere_id,
            'filiere_nom'      => $i->filiere?->nom ?? '—',
            'niveau'           => $i->niveau,
            'annee_academique' => $i->annee_academique,
            'statut'           => $i->statut,
            'statut_label'     => $i->statut_label,
            'statut_bg'        => $i->statut_colors[0],
            'statut_fg'        => $i->statut_colors[1],
            'date_inscription' => $i->date_inscription?->format('Y-m-d') ?? '',
            'date_fr'          => $i->date_inscription?->format('d/m/Y') ?? '—',
            'notes'            => $i->notes ?? '',
        ];
    }

    private function getKpis(): array
    {
        return [
            'total'      => Inscription::count(),
            'en_attente' => Inscription::where('statut', 'en_attente')->count(),
            'validees'   => Inscription::where('statut', 'validee')->count(),
            'refusees'   => Inscription::where('statut', 'refusee')->count(),
        ];
    }
}
