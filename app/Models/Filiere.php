<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Filiere extends Model
{
    protected $fillable = ['nom', 'code', 'description', 'niveau_max', 'actif'];

    protected $casts = ['actif' => 'boolean'];

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    public function inscriptions(): HasMany
    {
        return $this->hasMany(Inscription::class);
    }
}
