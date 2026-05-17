<?php

namespace Database\Seeders;

use App\Models\AnneeAcademique;
use App\Models\Filiere;
use App\Models\Inscription;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Admin ────────────────────────────────────
        User::create([
            'name'     => 'Admin Super',
            'email'    => 'admin@campus.ma',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);

        // ── Années académiques ────────────────────────
        $annees = [
            ['label' => '2022-2023', 'date_debut' => '2022-09-01', 'date_fin' => '2023-06-30', 'actif' => false, 'ouvert_inscriptions' => false, 'description' => 'Année académique 2022-2023 clôturée.'],
            ['label' => '2023-2024', 'date_debut' => '2023-09-01', 'date_fin' => '2024-06-30', 'actif' => false, 'ouvert_inscriptions' => false, 'description' => 'Année académique 2023-2024 clôturée.'],
            ['label' => '2024-2025', 'date_debut' => '2024-09-01', 'date_fin' => '2025-06-30', 'actif' => true,  'ouvert_inscriptions' => true,  'description' => 'Année académique en cours.'],
            ['label' => '2025-2026', 'date_debut' => '2025-09-01', 'date_fin' => '2026-06-30', 'actif' => false, 'ouvert_inscriptions' => false, 'description' => 'Prochaine année académique.'],
        ];
        foreach ($annees as $a) AnneeAcademique::create($a);

        // ── Filières ─────────────────────────────────
        $filieres = [
            ['nom' => 'Informatique',       'code' => 'INFO', 'description' => 'Licence et Master en Informatique et Systèmes', 'niveau_max' => 'M2', 'actif' => true],
            ['nom' => 'Économie & Gestion', 'code' => 'ECO',  'description' => 'Sciences économiques et gestion d\'entreprise', 'niveau_max' => 'M2', 'actif' => true],
            ['nom' => 'Droit',              'code' => 'DRT',  'description' => 'Droit privé, public et sciences juridiques',    'niveau_max' => 'M2', 'actif' => true],
            ['nom' => 'Sciences',           'code' => 'SCI',  'description' => 'Sciences physiques, chimiques et biologiques',  'niveau_max' => 'M2', 'actif' => true],
            ['nom' => 'Lettres & Langues',  'code' => 'LET',  'description' => 'Littérature, linguistique et langues',          'niveau_max' => 'L3', 'actif' => true],
            ['nom' => 'Génie Civil',        'code' => 'GC',   'description' => 'Construction, BTP et génie des structures',     'niveau_max' => 'M2', 'actif' => false],
        ];
        $createdFilieres = [];
        foreach ($filieres as $f) $createdFilieres[] = Filiere::create($f);

        // ── Étudiants ────────────────────────────────
        $studentsData = [
            ['Ahmed','Benali','M','2001-03-15','Marocaine','actif',0,'L2','+212 6 12 34 56 78','ahmed.benali@email.com'],
            ['Fatima','El Idrissi','F','2000-07-22','Marocaine','actif',1,'M1','+212 6 23 45 67 89','fatima.idrissi@email.com'],
            ['Youssef','Benaoui','M','1999-11-08','Marocaine','actif',2,'L3','+212 6 34 56 78 90','youssef.benaoui@email.com'],
            ['Meryem','Cherkaoui','F','2002-05-30','Marocaine','inactif',3,'L1','+212 6 45 67 89 01','meryem.cherkaoui@email.com'],
            ['Mohammed','Alaoui','M','1998-09-14','Marocaine','actif',0,'M2','+212 6 56 78 90 12','mo.alaoui@email.com'],
            ['Aicha','Touati','F','2001-12-03','Marocaine','actif',1,'L2','+212 6 67 89 01 23','aicha.touati@email.com'],
            ['Karim','Mansouri','M','1997-04-19','Marocaine','diplome',2,'M2','+212 6 78 90 12 34','karim.mansouri@email.com'],
            ['Nadia','Belkadi','F','2000-08-27','Marocaine','actif',3,'L3','+212 6 89 01 23 45','nadia.belkadi@email.com'],
            ['Omar','Tahiri','M','2001-01-11','Marocaine','actif',0,'L1','+212 6 90 12 34 56','omar.tahiri@email.com'],
            ['Sara','Moussaoui','F','2002-06-25','Française','actif',1,'L3','+212 6 01 23 45 67','sara.moussaoui@email.com'],
            ['Hamza','Berrada','M','1999-02-14','Marocaine','suspendu',4,'L2','+212 6 11 22 33 44','hamza.berrada@email.com'],
            ['Zineb','Fassi','F','2000-10-07','Marocaine','actif',5,'M1','+212 6 22 33 44 55','zineb.fassi@email.com'],
            ['Amine','Lahlou','M','2001-09-18','Marocaine','actif',0,'L3','+212 6 33 44 55 66','amine.lahlou@email.com'],
            ['Khadija','Ouali','F','2000-03-29','Marocaine','actif',2,'M1','+212 6 44 55 66 77','khadija.ouali@email.com'],
            ['Bilal','Naciri','M','1998-07-05','Marocaine','diplome',3,'L3','+212 6 55 66 77 88','bilal.naciri@email.com'],
        ];

        $createdStudents = [];
        foreach ($studentsData as $i => [$prenom,$nom,$sexe,$dob,$nat,$statut,$filiereIdx,$niveau,$tel,$email]) {
            $code      = strtoupper(substr($createdFilieres[$filiereIdx]->code, 0, 3));
            $num       = str_pad($i + 1, 3, '0', STR_PAD_LEFT);
            $createdStudents[] = Student::create([
                'prenom'          => $prenom,
                'nom'             => $nom,
                'email'           => $email,
                'telephone'       => $tel,
                'sexe'            => $sexe,
                'date_naissance'  => $dob,
                'matricule'       => "{$code}2024{$num}",
                'nationalite'     => $nat,
                'statut'          => $statut,
                'filiere_id'      => $createdFilieres[$filiereIdx]->id,
                'niveau'          => $niveau,
                'annee_academique'=> '2024-2025',
            ]);
        }

        // ── Inscriptions ─────────────────────────────
        $statutsInscription = ['validee','validee','validee','en_attente','validee','validee','validee','validee','en_attente','validee','refusee','validee','validee','validee','validee'];
        foreach ($createdStudents as $i => $student) {
            Inscription::create([
                'student_id'       => $student->id,
                'filiere_id'       => $student->filiere_id,
                'niveau'           => $student->niveau,
                'annee_academique' => '2024-2025',
                'statut'           => $statutsInscription[$i],
                'date_inscription' => now()->subDays(rand(1, 120))->format('Y-m-d'),
                'notes'            => null,
            ]);
        }

        // Quelques inscriptions d'années précédentes
        foreach (array_slice($createdStudents, 0, 5) as $student) {
            Inscription::create([
                'student_id'       => $student->id,
                'filiere_id'       => $student->filiere_id,
                'niveau'           => match($student->niveau) { 'L2'=>'L1','L3'=>'L2','M1'=>'L3','M2'=>'M1',default=>'L1' },
                'annee_academique' => '2023-2024',
                'statut'           => 'validee',
                'date_inscription' => '2023-09-15',
            ]);
        }
    }
}
