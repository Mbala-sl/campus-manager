@extends('layouts.admin')

@section('title', ($page ?? 'Page') . ' — Campus Manager')

@section('content')
<div style="flex:1; display:flex; align-items:center; justify-content:center; padding:40px;">
    <div style="max-width:520px; width:100%; text-align:center;">

        <!-- Icône cadenas animée -->
        <div style="position:relative; display:inline-flex; align-items:center; justify-content:center; margin-bottom:32px;">
            <div style="width:100px; height:100px; border-radius:28px; background:linear-gradient(135deg,#F1F5F9,#E2E8F0); display:flex; align-items:center; justify-content:center; position:relative;">
                <svg width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                </svg>
                <!-- Badge "bientôt" -->
                <div style="position:absolute; top:-8px; right:-8px; background:linear-gradient(135deg,#16A34A,#15803D); border-radius:999px; padding:4px 10px; font-size:10px; font-weight:700; color:white; white-space:nowrap; box-shadow:0 4px 10px rgba(22,163,74,.3);">
                    Bientôt
                </div>
            </div>
        </div>

        <!-- Titre -->
        <h1 style="font-size:28px; font-weight:800; color:#0F172A; margin:0 0 10px; letter-spacing:-.5px;">
            {{ $page ?? 'Page' }}
        </h1>
        <p style="font-size:15px; color:#64748B; line-height:1.7; margin:0 0 28px; max-width:400px; margin-left:auto; margin-right:auto;">
            {{ $description ?? 'Cette section est en cours de développement et sera disponible prochainement.' }}
        </p>

        <!-- Progress bar décorative -->
        <div style="background:#F1F5F9; border-radius:999px; height:6px; margin:0 auto 28px; max-width:280px; overflow:hidden;">
            <div style="height:100%; width:65%; background:linear-gradient(90deg,#16A34A,#22C55E); border-radius:999px;"></div>
        </div>
        <p style="font-size:12px; color:#94A3B8; margin:0 0 32px;">En développement · 65% complété</p>

        <!-- Fonctionnalités à venir -->
        <div style="background:#FFFFFF; border:1px solid #F1F5F9; border-radius:18px; padding:22px 24px; text-align:left; margin-bottom:28px; box-shadow:0 2px 10px rgba(15,23,42,.04);">
            <p style="font-size:12px; font-weight:700; color:#94A3B8; text-transform:uppercase; letter-spacing:.6px; margin:0 0 14px;">Ce qui est prévu</p>
            @php
            $features = match($page ?? '') {
                'Utilisateurs'       => ['Gestion des comptes administrateurs','Attribution des rôles et permissions','Historique des connexions','Réinitialisation des mots de passe'],
                'Paiements'          => ['Suivi des frais de scolarité','Génération de reçus PDF','Tableaux de bord financiers','Rappels automatiques de paiement'],
                'Exportations'       => ['Export CSV et Excel des étudiants','Rapports PDF personnalisés','Exportation des inscriptions','Sauvegarde complète des données'],
                'Journal d\'activité'=> ['Historique de toutes les actions','Filtrage par utilisateur et date','Export du journal','Alertes sur actions sensibles'],
                'Statistiques'       => ['Graphiques d\'évolution détaillés','Analyses par filière et niveau','Taux de réussite et abandons','Comparaisons inter-années'],
                'Notifications'      => ['Envoi d\'emails aux étudiants','Alertes de paiement et échéances','Notifications push admin','Modèles de messages personnalisables'],
                'Paramètres'         => ['Logo et identité de l\'établissement','Configuration des années académiques','Personnalisation des emails','Gestion des sauvegardes'],
                default              => ['Interface complète de gestion','Tableaux de bord analytiques','Exports et rapports','Support technique dédié'],
            };
            @endphp
            <div style="display:flex; flex-direction:column; gap:10px;">
                @foreach($features as $f)
                <div style="display:flex; align-items:center; gap:10px;">
                    <div style="width:20px; height:20px; border-radius:6px; background:#F0FDF4; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#16A34A" stroke-width="2.5" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>
                    </div>
                    <span style="font-size:13px; color:#475569;">{{ $f }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <!-- CTA -->
        <a href="{{ route('dashboard') }}" style="display:inline-flex; align-items:center; gap:8px; height:46px; padding:0 28px; background:#16A34A; color:#fff; border-radius:13px; font-size:14px; font-weight:600; text-decoration:none; box-shadow:0 4px 14px rgba(22,163,74,.22); transition:transform .18s, box-shadow .18s;" onmouseover="this.style.transform='translateY(-1px)';this.style.boxShadow='0 8px 20px rgba(22,163,74,.28)'" onmouseout="this.style.transform='';this.style.boxShadow='0 4px 14px rgba(22,163,74,.22)'">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round"><polyline points="15 18 9 12 15 6"/></svg>
            Retour au tableau de bord
        </a>
    </div>
</div>
@endsection
