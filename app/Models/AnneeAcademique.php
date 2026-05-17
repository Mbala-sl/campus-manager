<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnneeAcademique extends Model
{
    protected $table = 'annees_academiques';

    protected $fillable = [
        'label', 'date_debut', 'date_fin',
        'actif', 'ouvert_inscriptions', 'description',
    ];

    protected $casts = [
        'date_debut'          => 'date',
        'date_fin'            => 'date',
        'actif'               => 'boolean',
        'ouvert_inscriptions' => 'boolean',
    ];

    public function countStudents(): int
    {
        return Student::where('annee_academique', $this->label)->count();
    }

    public function countInscriptions(): int
    {
        return Inscription::where('annee_academique', $this->label)->count();
    }

    public function getStatutLabelAttribute(): string
    {
        if ($this->actif) return 'En cours';
        return now()->lt($this->date_debut) ? 'À venir' : 'Terminée';
    }

    public function getStatutColorsAttribute(): array
    {
        if ($this->actif) return ['#DCFCE7', '#15803D'];
        return now()->lt($this->date_debut)
            ? ['#EFF6FF', '#1D4ED8']
            : ['#F1F5F9', '#475569'];
    }
}
