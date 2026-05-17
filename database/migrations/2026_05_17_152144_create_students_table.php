<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('prenom');
            $table->string('nom');
            $table->string('email')->unique();
            $table->string('telephone', 30)->nullable();
            $table->enum('sexe', ['M', 'F'])->default('M');
            $table->date('date_naissance')->nullable();
            $table->string('matricule', 30)->unique();
            $table->string('adresse')->nullable();
            $table->string('nationalite', 60)->default('Marocaine');
            $table->string('photo')->nullable();
            $table->enum('statut', ['actif', 'inactif', 'diplome', 'suspendu'])->default('actif');
            $table->foreignId('filiere_id')->nullable()->constrained('filieres')->nullOnDelete();
            $table->string('niveau', 10)->default('L1'); // L1,L2,L3,M1,M2
            $table->string('annee_academique', 20)->default('2024-2025');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
