<?php

namespace App\Http\Controllers;

use App\Models\AnneeAcademique;
use App\Models\Inscription;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AnneeAcademiqueController extends Controller
{
    public function index(Request $request)
    {
        $annees = AnneeAcademique::orderByDesc('date_debut')->paginate(10);

        $annees->getCollection()->transform(function ($annee) {
            $annee->nb_etudiants    = Student::where('annee_academique', $annee->label)->count();
            $annee->nb_inscriptions = Inscription::where('annee_academique', $annee->label)->count();
            return $annee;
        });

        $kpis = [
            'total'    => AnneeAcademique::count(),
            'actuelle' => AnneeAcademique::where('actif', true)->count(),
            'ouvertes' => AnneeAcademique::where('ouvert_inscriptions', true)->count(),
            'etudiants'=> Student::where('annee_academique', AnneeAcademique::where('actif', true)->value('label') ?? '2024-2025')->count(),
        ];

        return view('annees.index', compact('annees', 'kpis'));
    }

    public function store(Request $request)
    {
        // FIX : normalize booleans avant validation
        $request->merge([
            'actif'               => $request->boolean('actif'),
            'ouvert_inscriptions' => $request->boolean('ouvert_inscriptions'),
        ]);

        $data = $request->validate([
            'label'               => ['required', 'string', 'max:20', 'unique:annees_academiques,label', 'regex:/^\d{4}-\d{4}$/'],
            'date_debut'          => ['required', 'date'],
            'date_fin'            => ['required', 'date', 'after:date_debut'],
            'actif'               => ['required', 'boolean'],
            'ouvert_inscriptions' => ['required', 'boolean'],
            'description'         => ['nullable', 'string', 'max:500'],
        ]);

        if ($data['actif']) {
            AnneeAcademique::where('actif', true)->update(['actif' => false]);
        }

        $annee = AnneeAcademique::create($data);

        return response()->json([
            'success' => true,
            'message' => "L'année académique {$annee->label} a été créée.",
            'annee'   => $this->format($annee),
            'kpis'    => $this->getKpis(),
        ]);
    }

    public function update(Request $request, AnneeAcademique $annee)
    {
        $request->merge([
            'actif'               => $request->boolean('actif'),
            'ouvert_inscriptions' => $request->boolean('ouvert_inscriptions'),
        ]);

        $data = $request->validate([
            'label'               => ['required', 'string', 'max:20', Rule::unique('annees_academiques', 'label')->ignore($annee), 'regex:/^\d{4}-\d{4}$/'],
            'date_debut'          => ['required', 'date'],
            'date_fin'            => ['required', 'date', 'after:date_debut'],
            'actif'               => ['required', 'boolean'],
            'ouvert_inscriptions' => ['required', 'boolean'],
            'description'         => ['nullable', 'string', 'max:500'],
        ]);

        if ($data['actif'] && !$annee->actif) {
            AnneeAcademique::where('actif', true)->update(['actif' => false]);
        }

        $annee->update($data);

        return response()->json([
            'success' => true,
            'message' => "L'année {$annee->label} a été mise à jour.",
            'annee'   => $this->format($annee),
            'kpis'    => $this->getKpis(),
        ]);
    }

    public function destroy(AnneeAcademique $annee)
    {
        if ($annee->actif) {
            return response()->json([
                'success' => false,
                'message' => 'Impossible de supprimer l\'année académique en cours.',
            ], 422);
        }

        $label = $annee->label;
        $annee->delete();

        return response()->json([
            'success' => true,
            'message' => "L'année académique {$label} a été supprimée.",
            'kpis'    => $this->getKpis(),
        ]);
    }

    private function format(AnneeAcademique $a): array
    {
        return [
            'id'                  => $a->id,
            'label'               => $a->label,
            'date_debut'          => $a->date_debut?->format('Y-m-d'),
            'date_debut_fr'       => $a->date_debut?->format('d/m/Y'),
            'date_fin'            => $a->date_fin?->format('Y-m-d'),
            'date_fin_fr'         => $a->date_fin?->format('d/m/Y'),
            'actif'               => $a->actif,
            'ouvert_inscriptions' => $a->ouvert_inscriptions,
            'description'         => $a->description ?? '',
            'statut_label'        => $a->statut_label,
            'statut_bg'           => $a->statut_colors[0],
            'statut_fg'           => $a->statut_colors[1],
            'nb_etudiants'        => Student::where('annee_academique', $a->label)->count(),
            'nb_inscriptions'     => Inscription::where('annee_academique', $a->label)->count(),
        ];
    }

    private function getKpis(): array
    {
        return [
            'total'    => AnneeAcademique::count(),
            'actuelle' => AnneeAcademique::where('actif', true)->count(),
            'ouvertes' => AnneeAcademique::where('ouvert_inscriptions', true)->count(),
            'etudiants'=> Student::where('annee_academique', AnneeAcademique::where('actif', true)->value('label') ?? '2024-2025')->count(),
        ];
    }
}
