<?php

namespace App\Http\Controllers;

use App\Models\Filiere;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FiliereController extends Controller
{
    public function index(Request $request)
    {
        $query = Filiere::withCount('students');

        if ($request->filled('statut') && $request->statut !== 'toutes') {
            $query->where('actif', $request->statut === 'actif');
        }

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(fn($qb) => $qb->where('nom', 'like', "%$q%")->orWhere('code', 'like', "%$q%"));
        }

        $filieres = $query->orderBy('nom')->paginate(12)->withQueryString();

        $kpis = [
            'total'    => Filiere::count(),
            'actives'  => Filiere::where('actif', true)->count(),
            'inactives'=> Filiere::where('actif', false)->count(),
            'etudiants'=> Student::count(),
        ];

        return view('filieres.index', compact('filieres', 'kpis'));
    }

    public function store(Request $request)
    {
        // FIX : normalize boolean AVANT validation (HTML checkbox envoie "on", pas true/false)
        $request->merge(['actif' => $request->boolean('actif', true)]);

        $data = $request->validate([
            'nom'        => ['required', 'string', 'max:100'],
            'code'       => ['required', 'string', 'max:20', 'unique:filieres,code'],
            'description'=> ['nullable', 'string', 'max:500'],
            'niveau_max' => ['required', 'in:L1,L2,L3,M1,M2'],
            'actif'      => ['required', 'boolean'],
        ]);

        $data['code'] = strtoupper($data['code']);
        $filiere = Filiere::create($data);
        $filiere->loadCount('students');

        return response()->json([
            'success' => true,
            'message' => "La filière \"{$filiere->nom}\" a été créée avec succès.",
            'filiere' => $this->format($filiere),
            'kpis'    => $this->getKpis(),
        ]);
    }

    public function update(Request $request, Filiere $filiere)
    {
        $request->merge(['actif' => $request->boolean('actif', true)]);

        $data = $request->validate([
            'nom'        => ['required', 'string', 'max:100'],
            'code'       => ['required', 'string', 'max:20', Rule::unique('filieres', 'code')->ignore($filiere)],
            'description'=> ['nullable', 'string', 'max:500'],
            'niveau_max' => ['required', 'in:L1,L2,L3,M1,M2'],
            'actif'      => ['required', 'boolean'],
        ]);

        $data['code'] = strtoupper($data['code']);
        $filiere->update($data);
        $filiere->loadCount('students');

        return response()->json([
            'success' => true,
            'message' => "La filière \"{$filiere->nom}\" a été mise à jour.",
            'filiere' => $this->format($filiere),
            'kpis'    => $this->getKpis(),
        ]);
    }

    public function destroy(Filiere $filiere)
    {
        $filiere->loadCount('students');

        if ($filiere->students_count > 0) {
            return response()->json([
                'success' => false,
                'message' => "Impossible : {$filiere->students_count} étudiant(s) inscrits dans cette filière.",
            ], 422);
        }

        $name = $filiere->nom;
        $filiere->delete();

        return response()->json([
            'success' => true,
            'message' => "La filière \"{$name}\" a été supprimée.",
            'kpis'    => $this->getKpis(),
        ]);
    }

    private function format(Filiere $f): array
    {
        return [
            'id'             => $f->id,
            'nom'            => $f->nom,
            'code'           => $f->code,
            'description'    => $f->description ?? '',
            'niveau_max'     => $f->niveau_max,
            'actif'          => $f->actif,
            'students_count' => $f->students_count,
        ];
    }

    private function getKpis(): array
    {
        return [
            'total'    => Filiere::count(),
            'actives'  => Filiere::where('actif', true)->count(),
            'inactives'=> Filiere::where('actif', false)->count(),
            'etudiants'=> Student::count(),
        ];
    }
}
