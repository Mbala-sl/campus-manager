<?php

namespace App\Http\Controllers;

use App\Models\Filiere;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::with('filiere')->latest();

        if ($request->filled('statut') && $request->statut !== 'tous') {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('prenom', 'like', "%{$search}%")
                  ->orWhere('nom', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('matricule', 'like', "%{$search}%");
            });
        }

        $students = $query->paginate(12)->withQueryString();
        $filieres = Filiere::where('actif', true)->orderBy('nom')->get();

        $kpis = [
            'total'    => Student::count(),
            'actifs'   => Student::where('statut', 'actif')->count(),
            'nouveaux' => Student::where('annee_academique', '2024-2025')->count(),
            'diplomes' => Student::where('statut', 'diplome')->count(),
        ];

        return view('students.index', compact('students', 'filieres', 'kpis'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'prenom'           => ['required', 'string', 'max:80'],
            'nom'              => ['required', 'string', 'max:80'],
            'email'            => ['required', 'email', 'unique:students,email'],
            'telephone'        => ['nullable', 'string', 'max:30'],
            'sexe'             => ['required', 'in:M,F'],
            'date_naissance'   => ['nullable', 'date'],
            'matricule'        => ['required', 'string', 'unique:students,matricule'],
            'adresse'          => ['nullable', 'string', 'max:200'],
            'nationalite'      => ['nullable', 'string', 'max:60'],
            'statut'           => ['required', 'in:actif,inactif,diplome,suspendu'],
            'filiere_id'       => ['nullable', 'exists:filieres,id'],
            'niveau'           => ['required', 'in:L1,L2,L3,M1,M2'],
            'annee_academique' => ['required', 'string', 'max:20'],
        ]);

        $student = Student::create($data);
        $student->load('filiere');

        return response()->json([
            'success' => true,
            'message' => "{$student->prenom} {$student->nom} a été ajouté avec succès.",
            'student' => $this->format($student),
            'kpis'    => $this->getKpis(),
        ]);
    }

    public function update(Request $request, Student $student)
    {
        $data = $request->validate([
            'prenom'           => ['required', 'string', 'max:80'],
            'nom'              => ['required', 'string', 'max:80'],
            'email'            => ['required', 'email', Rule::unique('students', 'email')->ignore($student)],
            'telephone'        => ['nullable', 'string', 'max:30'],
            'sexe'             => ['required', 'in:M,F'],
            'date_naissance'   => ['nullable', 'date'],
            'matricule'        => ['required', 'string', Rule::unique('students', 'matricule')->ignore($student)],
            'adresse'          => ['nullable', 'string', 'max:200'],
            'nationalite'      => ['nullable', 'string', 'max:60'],
            'statut'           => ['required', 'in:actif,inactif,diplome,suspendu'],
            'filiere_id'       => ['nullable', 'exists:filieres,id'],
            'niveau'           => ['required', 'in:L1,L2,L3,M1,M2'],
            'annee_academique' => ['required', 'string', 'max:20'],
        ]);

        $student->update($data);
        $student->load('filiere');

        return response()->json([
            'success' => true,
            'message' => "Le dossier de {$student->prenom} {$student->nom} a été mis à jour.",
            'student' => $this->format($student),
            'kpis'    => $this->getKpis(),
        ]);
    }

    public function destroy(Student $student)
    {
        $name = $student->nom_complet;
        $student->delete();

        return response()->json([
            'success' => true,
            'message' => "{$name} a été supprimé.",
            'kpis'    => $this->getKpis(),
        ]);
    }

    private function format(Student $s): array
    {
        return [
            'id'              => $s->id,
            'nom_complet'     => $s->nom_complet,
            'initiale'        => $s->initiale,
            'prenom'          => $s->prenom,
            'nom'             => $s->nom,
            'email'           => $s->email,
            'telephone'       => $s->telephone ?? '—',
            'matricule'       => $s->matricule,
            'filiere_id'      => $s->filiere_id ?? '',
            'filiere_nom'     => $s->filiere?->nom ?? '—',
            'niveau'          => $s->niveau,
            'annee_academique'=> $s->annee_academique,
            'adresse'         => $s->adresse ?? '',
            'nationalite'     => $s->nationalite ?? '',
            'sexe'            => $s->sexe,
            'date_naissance'  => $s->date_naissance?->format('Y-m-d') ?? '',
            'statut'          => $s->statut,
            'statut_label'    => $s->statut_label,
            'statut_bg'       => $s->statut_colors[0],
            'statut_fg'       => $s->statut_colors[1],
        ];
    }

    private function getKpis(): array
    {
        return [
            'total'    => Student::count(),
            'actifs'   => Student::where('statut', 'actif')->count(),
            'nouveaux' => Student::where('annee_academique', '2024-2025')->count(),
            'diplomes' => Student::where('statut', 'diplome')->count(),
        ];
    }
}
