<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inscription extends Model
{
    protected $fillable = [
        'student_id', 'filiere_id', 'niveau',
        'annee_academique', 'statut', 'date_inscription', 'notes',
    ];

    protected $casts = ['date_inscription' => 'date'];

    public function getStatutLabelAttribute(): string
    {
        return match($this->statut) {
            'en_attente' => 'En attente',
            'validee'    => 'Validée',
            'refusee'    => 'Refusée',
            default      => $this->statut,
        };
    }

    public function getStatutColorsAttribute(): array
    {
        return match($this->statut) {
            'en_attente' => ['#FEF3C7', '#D97706'],
            'validee'    => ['#DCFCE7', '#15803D'],
            'refusee'    => ['#FEE2E2', '#DC2626'],
            default      => ['#F1F5F9', '#475569'],
        };
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function filiere(): BelongsTo
    {
        return $this->belongsTo(Filiere::class);
    }
}
