<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    protected $fillable = [
        'prenom', 'nom', 'email', 'telephone', 'sexe',
        'date_naissance', 'matricule', 'adresse', 'nationalite',
        'photo', 'statut', 'filiere_id', 'niveau', 'annee_academique',
    ];

    protected $casts = ['date_naissance' => 'date'];

    public function filiere(): BelongsTo
    {
        return $this->belongsTo(Filiere::class);
    }

    public function inscriptions(): HasMany
    {
        return $this->hasMany(Inscription::class);
    }

    public function getNomCompletAttribute(): string
    {
        return $this->nom . ' ' . $this->prenom;
    }

    public function getInitialeAttribute(): string
    {
        return strtoupper(substr($this->nom, 0, 1));
    }

    public function getStatutLabelAttribute(): string
    {
        return match($this->statut) {
            'actif'     => 'Actif',
            'inactif'   => 'Inactif',
            'diplome'   => 'Diplômé',
            'suspendu'  => 'Suspendu',
            default     => $this->statut,
        };
    }

    public function getStatutColorsAttribute(): array
    {
        return match($this->statut) {
            'actif'    => ['#DCFCE7', '#15803D'],
            'inactif'  => ['#FEE2E2', '#DC2626'],
            'diplome'  => ['#EFF6FF', '#1D4ED8'],
            'suspendu' => ['#FEF3C7', '#D97706'],
            default    => ['#F1F5F9', '#475569'],
        };
    }
}
