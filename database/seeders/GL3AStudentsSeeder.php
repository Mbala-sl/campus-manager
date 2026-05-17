<?php

namespace Database\Seeders;

use App\Models\Filiere;
use App\Models\Student;
use Illuminate\Database\Seeder;

class GL3AStudentsSeeder extends Seeder
{
    public function run(): void
    {
        // ── Créer ou récupérer la filière Génie Logiciel ──────────────
        $filiere = Filiere::firstOrCreate(
            ['code' => 'GL'],
            [
                'nom'         => 'Génie Logiciel',
                'description' => 'Licence et Master en Génie Logiciel — Campus Yaoundé',
                'niveau_max'  => 'M2',
                'actif'       => true,
            ]
        );

        // ── Liste complète GL3A 2025-2026 ─────────────────────────────
        // Format : [nom, prenom, sexe, date_naissance (YYYY-MM-DD), matricule]
        $students = [
            ['ABOMO MBELE',          'DADDIE MORELLE',          'F', '2000-09-02', 'GL.CMR.D001.2324B'],
            ['AKONO AKONO',          'JUDICAEL RONELLE KEVIE',  'M', '1999-01-22', 'SR.CMR.Y002.2324A'],
            ['ALAKA MBIDA',          'KAMEL ARCHANGE',          'M', '2006-04-09', 'GL.CMR.Y003.2324G'],
            ['ALANG PRINCE',         'DANILO',                  'M', '2000-01-15', 'GL.CMR.Y003.2324A'],
            ['AMOUGOU ONDIGUI OWONA','JEAN FRANCOIS',            'M', '2005-01-21', 'GL.CMR.Y002.2324J'],
            ['ANANGA MVE',           'ESTELLE FLORA',           'F', '2005-02-07', 'GL.CMR.Y006.2324G'],
            ['AWATSA TILONG',        'SONIA',                   'F', '2004-04-04', 'GL.CMR.Y006.2324F'],
            ['AWONGUE SAM',          'ESTELLE BREA',            'F', '2001-06-29', 'GL.CMR.Y005.2324J'],
            ['AYISSI TSEKANE',       'MARC AUREL BOSCO',        'M', '2005-02-27', 'GL.CMR.Y008.2324F'],
            ['AZOUTSA ZONSOP',       'OVIANE ARELLE',           'F', '2005-10-08', 'GL.CMR.Y004.2324H'],
            ['BADOEDANA LEPEKE',     'JACQUELINE BLANCHE',      'F', '2006-01-08', 'GL.CMR.Yg001.2324'],
            ['BIBOUM',               'BERTHE DARLAINE MICHELE', 'F', '2002-01-19', 'GL.CMR.Y012.2324F'],
            ['BILEGUE ATANGANA',     'JACQUELINE NELYE',        'F', '2006-08-08', 'GL.CMR.MB008.2324'],
            ['BILOA FOUDA',          'THERESE LAURIELLE',       'F', '2004-12-16', 'GL.CMR.Y013.2324F'],
            ['BOGMIS BOGMIS',        'FRANCOIS WILSON',         'M', '2006-02-03', 'GL.CMR.Y014.2324F'],
            ['BOLO BOLO SAMUELA',    'SAMIRA SAMANTHA',         'F', '2007-01-05', 'GL.CMR.Y005.2324I'],
            ['DIDIER WOUEDA',        'SOREL',                   'M', '2005-07-17', 'GL.CMR.Y012.2324C'],
            ['DJOGA NGOUEM III',     'PARFAIT',                 'M', '2004-09-09', 'GL.CMR.Y011.2324A'],
            ['DJOUGWOU',             'CELESTIN',                'M', '2005-01-13', 'GL.CMR.G017.2324'],
            ['DSCHIDA DSAGUE',       'DERRICK',                 'M', '1998-01-02', 'GL.CMR.Y069.2425D'],
            ['DSIMO DZE',            'CHARLES AGNOR',           'M', '2005-05-28', 'GL.CMR.Y016.2324F'],
            ['ELONG ELONG',          'JEAN CALVIN',             'M', '2004-03-10', 'GL.CMR.Y013.2324A'],
            ['ENANGUE MAYEYE',       'ARISTIDE',                'M', '2004-10-09', 'SR.CMR.Y015.2324A'],
            ['ENGUENE MENOUNGA',     'ANNE CLARA',              'F', '2006-05-25', 'GL.CMR.Y013.2324I'],
            ['ENGUENO NOUMBOU',      'ARIEL HERMANN',           'M', '2005-10-26', 'GL.CMR.Y010.2324H'],
            ['ENYEGUE',              'SATURNIN FERNAND',        'M', '2004-06-27', 'GL.CMR.Y016.2324D'],
            ['ESSAMBO MBELLA',       'CHARLES ARTHUR',          'M', '2001-07-30', 'GL.CMR.Y017.2324D'],
            ['ETEME MESSI',          'FRANK KENNY',             'M', '2005-07-05', 'GL.CMR.Y018.2324A'],
            ['ETOGA ESSOA',          'PRINCE WILLIAMS',         'M', '2003-01-05', 'GL.CMR.D013.2324A'],
            ['FEUTIO FOMEKONG',      'FRED BRISTON',            'M', '2006-03-09', 'GL.CMR.Y019.2324A'],
            ['FOBA NZOTEU',          'ALAIN',                   'M', '2005-10-08', 'GL.CMR.MB053.2324'],
            ['FOUDA',                'FERDINAND KEVIN',         'M', '2003-02-10', 'GL.CMR.MB018.2324'],
            ['KAMDJOUN KANKEU',      'ARICE BOREL',             'M', '2005-02-05', 'GL.CMR.Y016.2324J'],
            ['KAMENI KOUNGOUE',      'DIMITRY',                 'M', '2005-01-30', 'GL.CMR.D015.2324B'],
            ['KAMOGNE',              'FRANC CHATELAIN',         'M', '2002-11-06', 'GL.CMR.Y019.2324J'],
            ['KEMGANG DAMENI',       'DIBRIL ELOGE',            'M', '2002-08-03', 'GL.CMR.Y023.2324A'],
            ['KENFACK TONTSA',       'BRAYAN FRED',             'M', '2004-06-04', 'GL.CMR.B011.2223'],
            ['LINGOM',               'SIMON',                   'M', '2001-08-15', 'GL.CMR.Y025.2324D'],
            ['MADJOMOK MBOU',        'GAELLE',                  'F', '2003-12-03', 'GL.CMR.Y025.2324J'],
            ['MAFO TSAPI',           'YANNICK JEFF',            'M', '2005-08-17', 'SR.CMR.Y027.2324D'],
            ['MAHELE',               'HONORE GURVAN',           'M', '2007-10-16', 'GL.CMR.Y026.2324I'],
            ['MAKON BESSIGUI',       'NOE CEDRIC LE ROI',       'M', '2001-06-24', 'GL.CMR.Y029.2324A'],
            ['MALOCK',               'BERNADETTA MORALEX ELZA', 'F', '2002-10-09', 'GL.CMR.Y030.2324F'],
            ['MBALA MBILI',          'ESTHER FERNANDE',         'F', '2006-04-28', 'SR.CMR.MB022.2324'],
            ['MBALA ONANA',          'STEPHANE LANDRY',         'M', '1999-03-13', 'GL.CMR.Y031.2324A'],
            ['MBALLA',               'JUSTINE DANIELLE',        'F', '1998-10-08', 'GL.CMR.Y027.2324I'],
            ['MBALLA ABANDA',        'FIDELIN GERARD',          'M', '2004-09-21', 'GL.CMR.Y035.2324G'],
            ['MBIDA BIYEGUE MBARGA', 'REGINE LAETICIA',         'F', '1995-09-27', 'GL.CMR.Y030.2324I'],
            ['MBOULA DJOUGUELA',     'ELZIE',                   'F', '2002-03-19', 'SR.CMR.Y035.2324A'],
            ['MBOUOPDA MEWA',        'THEOPHILE',               'M', '2000-01-13', 'GL.CMR.Y036.2324A'],
            ['MEMONG ABE',           'REGINE ORNELA',           'F', '2006-11-02', 'GL.CMR.Y033.2324I'],
            ['MENGUE NAMA',          'WENDY FELICIA',           'F', '2006-07-20', 'GL.CMR.Y037.2324G'],
            ['MENGUE NYAMYA',        'DAVID',                   'M', '2006-12-11', 'GL.CMR.Y037.2324C'],
            ['MFOCHIVE ETOGA',       'FRANCK DJIBRIL',          'M', '2005-05-10', 'SR.CMR.MB028.2324'],
            ['MIDJANE EWANE',        'HELENE PATRICIA',         'F', '2005-01-26', 'GL.CMR.Y040.2324D'],
            ['MONEZAMBO ABATE',      'MARIE GENESE',            'F', '1999-10-22', 'SR.CMR.MB030.2324'],
            ['MOOH NYEMEG',          'ZACHEE JUNIOR',           'M', '2006-10-23', 'GL.CMR.Y041.2324D'],
            ['MVONDO',               'SIMON PIERRE JUNIOR',     'M', '2004-02-02', 'GL.CMR.Y046.2324G'],
            ['NCHANGVI',             'SEBASTIAN JUNIOR',        'M', '2005-11-03', 'SE.CMR.Y040.2324C'],
            ['NDAME LOBE',           'ARNAULD AUDREY',          'M', '2003-05-21', 'GL.CMR.Yg005.2324'],
            ['NDANGA NJEUMEN',       'ERWIN',                   'M', '1998-05-28', 'GL.CMR.Y031.2324H'],
            ['NDJEMBA',              'DANIEL BERENGER',         'M', '2006-02-13', 'GL.CMR.Y046.2324E'],
            ['NGA ONGUENE OYILE',    'KENZA',                   'F', '2005-02-12', 'SR.CMR.M003.2324'],
            ['NGAH NDZI',            'DESIRE PARFAIT',          'M', '2002-01-10', 'GL.CMR.Y045.2324A'],
            ['NGNIGHA SOPGOUA',      'BOREL',                   'M', '2006-05-05', 'GL.CMR.D058.2324'],
            ['NGOBISSAE',            'JULIENNE YVETTE',         'F', '2002-12-07', 'GL.CMR.MB037.2324'],
            ['NGONO FILS TIMM',      'ARNOLD',                  'M', '2006-04-10', 'GL.CMR.Y048.2324D'],
            ['NGUEDONG KENFACK',     'ALLAN ROSTAND',           'M', '2005-09-21', 'GL.CMR.Y049.2324E'],
            ['NGUETCHOU DOMGUEP',    'MAXIME',                  'M', '2005-04-27', 'SR.CMR.Y042.2324I'],
            ['NGUIMBOUS BASSANGEN',  'PRINCESSE DANIELLA',      'F', '2007-04-02', 'GL.CMR.Y043.2324I'],
            ['NGUIONGBOUNG MANGA',   'ANDRE NATHALIE',          'F', '2005-04-20', 'GL.CMR.Y051.2324E'],
            ['NINLA TIGWA',          'DIMITRI AUDREY',          'M', '2003-10-16', 'GL.CMR.B047.2324'],
            ['NJOMGANG NJOKO',       'YVES',                    'M', '2001-06-05', 'GL.CMR.Y044.2324H'],
            ['NKENG DIMITRI',        'SAVIO',                   'M', '2005-02-15', 'GL.CMR.B050.2324'],
            ['NLOUTSIRI MENGUE',     'LYDIE CHLOE',             'F', '2004-02-14', 'GL.CMR.Y048.2324J'],
            ['OBAMA OTTOU',          'JOSEPH BOREL',            'M', '1998-10-10', 'GL.CMR.Y051.2324A'],
            ['OMGBA',                'ANNE CHRISTINE',          'F', '2005-06-09', 'GL.CMR.D032.2324B'],
            ['ONGOLO',               'DOMINIQUE SAVIOLA',       'M', '2004-08-08', 'GL.CMR.Y052.2324J'],
            ['SAHAMENE MAGNIMO',     'CYRIALE',                 'M', '2006-04-24', 'GL.CMR.Y054.2324J'],
            ['SIMO',                 'MARIE BERTHE',            'F', '2000-11-06', 'GL.CMR.MB049.2324'],
            ['TCHEUTCHOUA TEGNE',    'NELLY GUYLENE',           'F', '2003-05-19', 'GL.CMR.Y058.2324B'],
            ['TINGUEU NGUIFO',       'SHIVANO',                 'M', '2005-03-09', 'SR.CMR.Y061.2324B'],
            ['TSAFACK NGATCHA',      'PATRICK JORDAN',          'M', '2005-11-11', 'GL.CMR.D047.2324A'],
        ];

        $inserted = 0;
        $skipped  = 0;

        foreach ($students as [$nom, $prenom, $sexe, $dob, $matricule]) {

            // Ignorer si matricule déjà en base
            if (Student::where('matricule', $matricule)->exists()) {
                $skipped++;
                continue;
            }

            // Générer un email unique
            $p    = $this->normalize(explode(' ', $prenom)[0]);
            $n    = $this->normalize(explode(' ', $nom)[0]);
            $base = "{$p}.{$n}@gl3a.iaicameroun.cm";
            $email = $base;
            $i = 1;
            while (Student::where('email', $email)->exists()) {
                $email = "{$p}.{$n}{$i}@gl3a.iaicameroun.cm";
                $i++;
            }

            Student::create([
                'prenom'           => $prenom,
                'nom'              => $nom,
                'email'            => $email,
                'telephone'        => null,
                'sexe'             => $sexe,
                'date_naissance'   => $dob,
                'matricule'        => $matricule,
                'adresse'          => null,
                'nationalite'      => 'Camerounaise',
                'photo'            => null,
                'statut'           => 'actif',
                'filiere_id'       => $filiere->id,
                'niveau'           => 'L3',
                'annee_academique' => '2025-2026',
            ]);

            $inserted++;
        }

        $this->command->info("GL3A import terminé : {$inserted} étudiants ajoutés, {$skipped} ignorés (doublon matricule).");
    }

    private function normalize(string $s): string
    {
        $s = mb_strtolower(trim($s));
        $map = [
            'à'=>'a','á'=>'a','â'=>'a','ã'=>'a','ä'=>'a','å'=>'a',
            'è'=>'e','é'=>'e','ê'=>'e','ë'=>'e',
            'ì'=>'i','í'=>'i','î'=>'i','ï'=>'i',
            'ò'=>'o','ó'=>'o','ô'=>'o','õ'=>'o','ö'=>'o',
            'ù'=>'u','ú'=>'u','û'=>'u','ü'=>'u',
            'ý'=>'y','ÿ'=>'y','ñ'=>'n','ç'=>'c','æ'=>'ae','œ'=>'oe',
        ];
        $s = strtr($s, $map);
        return preg_replace('/[^a-z0-9]/', '', $s);
    }
}
