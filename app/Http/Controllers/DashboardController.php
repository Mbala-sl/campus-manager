<?php

namespace App\Http\Controllers;

use App\Models\Filiere;
use App\Models\Inscription;
use App\Models\Student;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_students'    => Student::count(),
            'actif_students'    => Student::where('statut', 'actif')->count(),
            'total_filieres'    => Filiere::where('actif', true)->count(),
            'total_admins'      => User::count(),
        ];

        // Répartition par filière — triée par nombre d'étudiants décroissant
        // FIX: SQLite ne supporte pas HAVING sans GROUP BY → filtre PHP
        $repartition = Filiere::withCount('students')
            ->get(['id', 'nom', 'code'])
            ->filter(fn($f) => $f->students_count > 0)
            ->sortByDesc('students_count')
            ->values();

        // Inscriptions récentes (derniers étudiants ajoutés)
        $recent = Student::with('filiere')
            ->latest()
            ->take(5)
            ->get();

        // Activité récente (simulée à partir des dernières opérations)
        $activities = Student::with('filiere')
            ->latest('updated_at')
            ->take(4)
            ->get();

        return view('dashboard', compact('stats', 'repartition', 'recent', 'activities'));
    }
}
