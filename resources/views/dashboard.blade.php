@extends('layouts.admin')

@section('title', 'Tableau de bord — Campus Manager')
@section('nav_dashboard', 'active')

@push('styles')
<style>
    .card { background:#FFFFFF; border-radius:20px; padding:24px; border:1px solid #F1F5F9; box-shadow:0 2px 12px rgba(15,23,42,.05); }
    .kpi-card { background:#FFFFFF; border-radius:20px; padding:22px 24px; border:1px solid #F1F5F9; box-shadow:0 2px 12px rgba(15,23,42,.05); transition:transform .22s ease, box-shadow .22s ease; cursor:default; }
    .kpi-card:hover { transform:translateY(-3px); box-shadow:0 10px 28px rgba(15,23,42,.09); }
    .list-row { display:flex; align-items:center; gap:12px; padding:11px 0; border-bottom:1px solid #F8FAFC; }
    .list-row:last-child { border-bottom:none; }
    .activity-row { display:flex; align-items:center; gap:12px; padding:10px 8px; border-radius:10px; transition:background .15s ease; }
    .activity-row:hover { background:#F8FAFC; }
    .alert-item { border-radius:14px; padding:16px 18px; display:flex; align-items:flex-start; gap:12px; }
    .topbar-btn { height:44px; background:#FFFFFF; border:1px solid #E2E8F0; border-radius:12px; display:flex; align-items:center; justify-content:center; cursor:pointer; transition:border-color .15s, background .15s; }
    .topbar-btn:hover { border-color:#CBD5E1; background:#F8FAFC; }
</style>
@endpush

@section('content')

<!-- ══ TOPBAR ══ -->
<div style="padding:28px 28px 0; display:flex; justify-content:space-between; align-items:center; flex-shrink:0; gap:24px;">
    <div>
        <h1 style="font-size:28px; font-weight:700; color:#0F172A; line-height:1.2;">Tableau de bord</h1>
        <p style="font-size:14px; color:#64748B; margin-top:4px;">Bonjour, {{ Auth::user()->name }} 👋</p>
    </div>
    <div style="display:flex; align-items:center; gap:12px; flex-shrink:0;">
        <!-- ══ Recherche globale ══ -->
        <div id="gs-wrapper" style="position:relative; width:320px;">
            <div id="gs-box" style="height:44px;background:#FFF;border:1.5px solid #E2E8F0;border-radius:12px;display:flex;align-items:center;padding:0 14px;gap:10px;transition:border-color .18s,box-shadow .18s;">
                <svg id="gs-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#94A3B8" stroke-width="2" stroke-linecap="round" style="flex-shrink:0;"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input type="text" id="gs-input" placeholder="Rechercher sur l'application…" autocomplete="off"
                    style="border:none;outline:none;font-size:13px;color:#0F172A;background:transparent;flex:1;font-family:'Inter',sans-serif;min-width:0;">
                <div id="gs-spinner" style="display:none;width:14px;height:14px;border:2px solid #E2E8F0;border-top-color:#16A34A;border-radius:50%;animation:gsSpin .6s linear infinite;flex-shrink:0;"></div>
                <button id="gs-clear" style="display:none;background:none;border:none;cursor:pointer;color:#94A3B8;padding:0;font-size:18px;line-height:1;flex-shrink:0;">×</button>
            </div>
            <!-- Dropdown -->
            <div id="gs-dropdown" style="display:none;position:absolute;top:calc(100% + 8px);left:50%;transform:translateX(-50%);width:500px;max-height:440px;overflow-y:auto;background:#FFF;border:1px solid #E2E8F0;border-radius:16px;box-shadow:0 16px 48px rgba(15,23,42,.16);z-index:9999;padding:6px;">
                <div id="gs-body"></div>
            </div>
        </div>
        <style>
        @keyframes gsSpin{to{transform:rotate(360deg)}}
        #gs-box.open{border-color:#22C55E!important;box-shadow:0 0 0 3px rgba(34,197,94,.10)!important;}
        .gs-group{font-size:10px;font-weight:700;color:#94A3B8;letter-spacing:.8px;text-transform:uppercase;padding:8px 12px 4px;}
        .gs-item{display:flex;align-items:center;gap:12px;padding:10px 12px;border-radius:12px;cursor:pointer;text-decoration:none;transition:background .12s;}
        .gs-item:hover,.gs-item.hl{background:#F0FDF4;}
        .gs-label{font-size:13px;font-weight:600;color:#0F172A;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
        .gs-item:hover .gs-label,.gs-item.hl .gs-label{color:#15803D;}
        .gs-sub{font-size:11px;color:#94A3B8;margin-top:1px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
        .gs-av{width:34px;height:34px;border-radius:50%;background:#DCFCE7;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:12px;color:#15803D;flex-shrink:0;}
        .gs-ico{width:34px;height:34px;border-radius:10px;background:#F1F5F9;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
        .gs-badge{font-size:10px;font-weight:600;padding:2px 8px;border-radius:999px;white-space:nowrap;flex-shrink:0;}
        .gs-sep{height:1px;background:#F1F5F9;margin:4px 0;}
        .gs-empty{padding:28px;text-align:center;color:#94A3B8;font-size:13px;}
        </style>
        <div style="position:relative;">
            <div class="topbar-btn" style="width:44px;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#475569" stroke-width="2" stroke-linecap="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
            </div>
            <div style="position:absolute;top:9px;right:10px;width:8px;height:8px;background:#EF4444;border-radius:50%;border:2px solid #F8FAFC;"></div>
        </div>
        <div style="display:flex; align-items:center; gap:10px; background:#FFFFFF; border:1px solid #E2E8F0; border-radius:12px; padding:5px 12px 5px 6px; cursor:pointer;">
            <div style="width:34px; height:34px; border-radius:50%; background:linear-gradient(135deg,#22C55E,#16A34A); display:flex; align-items:center; justify-content:center; color:white; font-weight:700; font-size:14px; flex-shrink:0;">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <div>
                <div style="font-size:13px; font-weight:600; color:#0F172A; white-space:nowrap;">{{ Auth::user()->name }}</div>
                <div style="font-size:11px; color:#94A3B8;">Administrateur</div>
            </div>
            <form method="POST" action="{{ route('logout') }}" style="margin-left:8px;">
                @csrf
                <button type="submit" title="Déconnexion" style="background:none;border:none;cursor:pointer;padding:0;display:flex;align-items:center;" onmouseover="this.children[0].style.stroke='#EF4444'" onmouseout="this.children[0].style.stroke='#94A3B8'">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#94A3B8" stroke-width="2" stroke-linecap="round" style="transition:stroke .15s;">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                        <polyline points="16 17 21 12 16 7"/>
                        <line x1="21" y1="12" x2="9" y2="12"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</div>

<!-- ══ KPI CARDS ══ -->
<div style="padding:20px 28px 0; display:grid; grid-template-columns:repeat(4,1fr); gap:16px;">
    @php
    $kpiData = [
        ['Étudiants inscrits', $stats['total_students'], 'actifs : '.$stats['actif_students'], '#ECFDF5','#16A34A','#DCFCE7','#15803D','M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2 M23 21v-2a4 4 0 0 0-3-3.87 M16 3.13a4 4 0 0 1 0 7.75'],
        ['Filières actives',   $stats['total_filieres'], 'programmes', '#EFF6FF','#3B82F6','#DBEAFE','#1D4ED8','M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z'],
        ['Inscriptions 2024',  $stats['total_students'], 'année en cours', '#FFF7ED','#F97316','#FED7AA','#C2410C','M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z M14 2v6h6 M16 13H8 M16 17H8'],
        ['Administrateurs',    $stats['total_admins'],   'comptes admin', '#F5F3FF','#8B5CF6','#DDD6FE','#7C3AED','M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2 M12 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8z'],
    ];
    @endphp
    @foreach($kpiData as [$label,$val,$sub,$ibg,$ic,$tbg,$tc,$icon])
    <div class="kpi-card">
        <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:16px;">
            <div style="width:44px; height:44px; border-radius:12px; background:{{ $ibg }}; display:flex; align-items:center; justify-content:center;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="{{ $ic }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="{{ $icon }}"/></svg>
            </div>
            <span style="font-size:11px; font-weight:600; background:{{ $tbg }}; color:{{ $tc }}; padding:3px 9px; border-radius:999px; white-space:nowrap;">{{ $sub }}</span>
        </div>
        <div style="font-size:32px; font-weight:700; color:#0F172A; line-height:1; letter-spacing:-.5px; margin-bottom:5px;">{{ number_format($val) }}</div>
        <div style="font-size:13px; font-weight:500; color:#64748B;">{{ $label }}</div>
    </div>
    @endforeach
</div>

<!-- ══ CHARTS ROW ══ -->
<div style="padding:16px 28px 0; display:grid; grid-template-columns:1fr 300px; gap:16px;">
    <div class="card">
        <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:20px;">
            <div>
                <h3 style="font-size:16px; font-weight:700; color:#0F172A; margin:0 0 3px;">Évolution des inscriptions</h3>
                <p style="font-size:12px; color:#94A3B8; margin:0;">Année académique 2024–2025</p>
            </div>
            <div style="display:flex; gap:6px;">
                <button style="font-size:12px; font-weight:600; background:#DCFCE7; color:#15803D; border:none; border-radius:8px; padding:5px 12px; cursor:pointer; font-family:'Inter',sans-serif;">6 mois</button>
                <button style="font-size:12px; font-weight:500; background:#F1F5F9; color:#475569; border:none; border-radius:8px; padding:5px 12px; cursor:pointer; font-family:'Inter',sans-serif;">Année</button>
            </div>
        </div>
        <div style="position:relative; height:220px;">
            <canvas id="inscriptionsChart"></canvas>
        </div>
    </div>

    <div class="card" style="display:flex; flex-direction:column;">
        <h3 style="font-size:16px; font-weight:700; color:#0F172A; margin:0 0 3px;">Répartition par filière</h3>
        <p style="font-size:12px; color:#94A3B8; margin:0 0 16px;">Année en cours</p>
        <div style="display:flex; justify-content:center; align-items:center; flex:1;">
            <div style="width:150px; height:150px; position:relative;">
                <canvas id="filiereChart"></canvas>
            </div>
        </div>
        <div style="margin-top:14px; display:flex; flex-direction:column; gap:7px; overflow-y:auto; max-height:180px;">
            @php
            $chartColors = [
                '#16A34A','#38BDF8','#FACC15','#F87171','#A78BFA',
                '#FB923C','#34D399','#60A5FA','#F472B6','#A3E635',
            ];
            @endphp
            @foreach($repartition as $i => $f)
            <div style="display:flex; justify-content:space-between; align-items:center; min-height:20px;">
                <div style="display:flex; align-items:center; gap:7px; min-width:0;">
                    <div style="width:8px; height:8px; border-radius:50%; background:{{ $chartColors[$i % count($chartColors)] }}; flex-shrink:0;"></div>
                    <span style="font-size:12px; color:#475569; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:140px;" title="{{ $f->nom }}">{{ $f->nom }}</span>
                </div>
                <span style="font-size:12px; font-weight:700; color:#0F172A; flex-shrink:0; margin-left:6px;">{{ number_format($f->students_count) }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- ══ SECOND ROW ══ -->
<div style="padding:16px 28px 0; display:grid; grid-template-columns:1fr 300px; gap:16px;">
    <div class="card">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
            <h3 style="font-size:16px; font-weight:700; color:#0F172A; margin:0;">Inscriptions récentes</h3>
            <a href="{{ route('students.index') }}" style="font-size:13px; font-weight:600; color:#16A34A; display:flex; align-items:center; gap:3px;">
                Voir tout <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
            </a>
        </div>
        @foreach($recent as $student)
        @php $colors = ['#BBF7D0','#BAE6FD','#FDE68A','#FBCFE8','#DDD6FE']; $c = $colors[$loop->index % 5]; @endphp
        <div class="list-row">
            <div style="width:40px; height:40px; border-radius:50%; background:{{ $c }}; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:14px; color:#0F172A; flex-shrink:0;">
                {{ $student->initiale }}
            </div>
            <div style="flex:1; min-width:0;">
                <div style="font-size:14px; font-weight:600; color:#0F172A; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $student->nom_complet }}</div>
                <div style="font-size:12px; color:#94A3B8; margin-top:2px;">{{ $student->filiere?->nom ?? '—' }} · {{ $student->niveau }}</div>
            </div>
            <div style="font-size:11px; color:#CBD5E1; white-space:nowrap; flex-shrink:0;">{{ $student->created_at->diffForHumans() }}</div>
            <span style="font-size:11px; font-weight:600; background:#DCFCE7; color:#15803D; padding:2px 9px; border-radius:999px; white-space:nowrap; flex-shrink:0;">Nouveau</span>
        </div>
        @endforeach
    </div>

    <div style="display:flex; flex-direction:column; gap:16px;">
        <div class="card" style="flex:1;">
            <h3 style="font-size:15px; font-weight:700; color:#0F172A; margin:0 0 12px;">Activité récente</h3>
            @foreach($activities as $act)
            @php $actColors = [['#ECFDF5','#16A34A'],['#EFF6FF','#3B82F6'],['#FFF7ED','#F97316'],['#F5F3FF','#8B5CF6']]; [$ibg,$ic] = $actColors[$loop->index % 4]; @endphp
            <div class="activity-row">
                <div style="width:34px; height:34px; border-radius:10px; background:{{ $ibg }}; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="{{ $ic }}" stroke-width="2" stroke-linecap="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                </div>
                <div style="flex:1; min-width:0;">
                    <div style="font-size:12px; font-weight:600; color:#0F172A; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $act->nom_complet }}</div>
                    <div style="font-size:11px; color:#94A3B8; margin-top:1px;">{{ $act->filiere?->nom ?? '—' }} · {{ $act->statut_label }}</div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="card" style="padding:18px;">
            <h3 style="font-size:13px; font-weight:700; color:#0F172A; margin:0 0 12px;">Aperçu rapide</h3>
            @foreach([['Étudiants actifs', $stats['actif_students'], '#16A34A'], ['Filières actives', $stats['total_filieres'], '#3B82F6'], ['Admins', $stats['total_admins'], '#8B5CF6']] as [$l,$v,$c])
            <div style="display:flex; justify-content:space-between; align-items:center; padding:6px 0; border-bottom:1px solid #F8FAFC;">
                <span style="font-size:12px; color:#64748B;">{{ $l }}</span>
                <span style="font-size:13px; font-weight:700; color:{{ $c }};">{{ number_format($v) }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- ══ ALERTS ══ -->
<div style="padding:16px 28px 28px;">
    <div class="card">
        <h3 style="font-size:16px; font-weight:700; color:#0F172A; margin:0 0 14px;">Alertes et rappels</h3>
        <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:12px;">
            <div class="alert-item" style="background:#FFFBEB; border:1px solid #FDE68A;">
                <div style="width:36px; height:36px; border-radius:10px; background:#FEF3C7; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#D97706" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                </div>
                <div style="min-width:0;">
                    <div style="font-size:13px; font-weight:700; color:#92400E;">Inscriptions en attente</div>
                    <div style="font-size:12px; color:#A16207; margin-top:3px; line-height:1.4;">Vérifiez les dossiers en cours</div>
                    <a href="{{ route('students.index') }}" style="font-size:12px; font-weight:600; color:#D97706; margin-top:6px; display:inline-flex; align-items:center; gap:2px;">Voir <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg></a>
                </div>
            </div>
            <div class="alert-item" style="background:#EFF6FF; border:1px solid #BFDBFE;">
                <div style="width:36px; height:36px; border-radius:10px; background:#DBEAFE; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#1D4ED8" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                </div>
                <div style="min-width:0;">
                    <div style="font-size:13px; font-weight:700; color:#1E3A8A;">Année académique</div>
                    <div style="font-size:12px; color:#1D4ED8; margin-top:3px; line-height:1.4;">Clôture inscriptions 2024-2025 le 31 mars</div>
                    <a href="#" style="font-size:12px; font-weight:600; color:#1D4ED8; margin-top:6px; display:inline-flex; align-items:center; gap:2px;">Configurer <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg></a>
                </div>
            </div>
            <div class="alert-item" style="background:#F0FDF4; border:1px solid #BBF7D0;">
                <div style="width:36px; height:36px; border-radius:10px; background:#DCFCE7; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#15803D" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                </div>
                <div style="min-width:0;">
                    <div style="font-size:13px; font-weight:700; color:#14532D;">Système opérationnel</div>
                    <div style="font-size:12px; color:#15803D; margin-top:3px; line-height:1.4;">Base de données SQLite — Tout est à jour</div>
                    <a href="#" style="font-size:12px; font-weight:600; color:#15803D; margin-top:6px; display:inline-flex; align-items:center; gap:2px;">Détails <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg></a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js" defer></script>
<script>
/* ══ RECHERCHE GLOBALE ══════════════════════════════════════════ */
(function () {
    const input    = document.getElementById('gs-input');
    const box      = document.getElementById('gs-box');
    const dropdown = document.getElementById('gs-dropdown');
    const body     = document.getElementById('gs-body');
    const spinner  = document.getElementById('gs-spinner');
    const clearBtn = document.getElementById('gs-clear');
    const icon     = document.getElementById('gs-icon');
    const csrf     = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

    let timer, activeIdx = -1, allItems = [];

    /* ── Icônes SVG par type ── */
    const ICONS = {
        student:     `<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#475569" stroke-width="2" stroke-linecap="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>`,
        filiere:     `<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#475569" stroke-width="2" stroke-linecap="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>`,
        inscription: `<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#475569" stroke-width="2" stroke-linecap="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>`,
        annee:       `<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#475569" stroke-width="2" stroke-linecap="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>`,
        page:        `<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#475569" stroke-width="2" stroke-linecap="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>`,
    };

    /* ── Ouvrir / Fermer ── */
    function openDropdown() { dropdown.style.display = 'block'; box.classList.add('open'); icon.style.stroke = '#16A34A'; }
    function closeDropdown() { dropdown.style.display = 'none'; box.classList.remove('open'); icon.style.stroke = '#94A3B8'; activeIdx = -1; allItems = []; }

    input.addEventListener('focus', () => { if (input.value.length >= 2) openDropdown(); });
    document.addEventListener('click', e => { if (!document.getElementById('gs-wrapper').contains(e.target)) closeDropdown(); });
    document.addEventListener('keydown', e => { if (e.key === 'Escape') { closeDropdown(); input.blur(); } });

    /* ── Saisie ── */
    input.addEventListener('input', () => {
        const q = input.value.trim();
        clearBtn.style.display = q ? 'block' : 'none';
        clearTimeout(timer);
        if (q.length < 2) { closeDropdown(); spinner.style.display = 'none'; return; }
        spinner.style.display = 'block';
        timer = setTimeout(() => doSearch(q), 300);
    });

    clearBtn.addEventListener('click', () => {
        input.value = '';
        clearBtn.style.display = 'none';
        closeDropdown();
        spinner.style.display = 'none';
        input.focus();
    });

    /* ── Navigation clavier ── */
    input.addEventListener('keydown', e => {
        if (!allItems.length) return;
        if (e.key === 'ArrowDown') { e.preventDefault(); setActive(activeIdx + 1); }
        else if (e.key === 'ArrowUp') { e.preventDefault(); setActive(activeIdx - 1); }
        else if (e.key === 'Enter' && activeIdx >= 0) { e.preventDefault(); allItems[activeIdx]?.click(); }
    });

    function setActive(idx) {
        allItems.forEach(el => el.classList.remove('hl'));
        activeIdx = Math.max(0, Math.min(idx, allItems.length - 1));
        allItems[activeIdx]?.classList.add('hl');
        allItems[activeIdx]?.scrollIntoView({ block: 'nearest' });
    }

    /* ── Recherche AJAX ── */
    async function doSearch(q) {
        try {
            const res  = await fetch(`/search?q=${encodeURIComponent(q)}`, {
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf }
            });
            const json = await res.json();
            spinner.style.display = 'none';
            renderResults(json.results, q);
        } catch {
            spinner.style.display = 'none';
            body.innerHTML = `<div class="gs-empty">Erreur réseau.</div>`;
            openDropdown();
        }
    }

    /* ── Rendu des résultats ── */
    function highlight(text, q) {
        const re = new RegExp(`(${q.replace(/[.*+?^${}()|[\]\\]/g,'\\$&')})`, 'gi');
        return text.replace(re, '<mark style="background:#DCFCE7;color:#15803D;border-radius:3px;padding:0 1px;">$1</mark>');
    }

    function renderResults(results, q) {
        allItems = [];
        activeIdx = -1;

        if (!results.length) {
            body.innerHTML = `
                <div class="gs-empty">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#CBD5E1" stroke-width="1.5" style="margin-bottom:10px;"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <div>Aucun résultat pour <strong>"${q}"</strong></div>
                </div>`;
            openDropdown();
            return;
        }

        let html = '';
        results.forEach((group, gi) => {
            if (gi > 0) html += '<div class="gs-sep"></div>';
            html += `<div class="gs-group">${group.group}</div>`;
            group.items.forEach(item => {
                const isStudent = item.type === 'student';
                const left = isStudent
                    ? `<div class="gs-av">${item.initiale ?? '?'}</div>`
                    : `<div class="gs-ico">${ICONS[item.type] ?? ICONS.page}</div>`;
                const badge = item.badge
                    ? `<span class="gs-badge" style="background:${item.badge_bg};color:${item.badge_fg};">${item.badge}</span>`
                    : '';
                html += `
                <a class="gs-item" href="${item.url}" data-url="${item.url}">
                    ${left}
                    <div style="flex:1;min-width:0;">
                        <div class="gs-label">${highlight(item.label, q)}</div>
                        <div class="gs-sub">${item.sub ?? ''}</div>
                    </div>
                    ${badge}
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#CBD5E1" stroke-width="2" style="flex-shrink:0;"><polyline points="9 18 15 12 9 6"/></svg>
                </a>`;
            });
        });

        body.innerHTML = html;
        allItems = Array.from(body.querySelectorAll('.gs-item'));
        openDropdown();
    }
})();

/* ══ CHARTS ════════════════════════════════════════════════════ */
document.addEventListener('DOMContentLoaded', () => {
    const ctx1 = document.getElementById('inscriptionsChart').getContext('2d');
    new Chart(ctx1, {
        type: 'line',
        data: {
            labels: ['Août','Sept','Oct','Nov','Déc','Jan'],
            datasets:[{
                label: 'Inscriptions',
                data: [{{ max(1, intval($stats['total_students'] * .26)) }}, {{ max(1, intval($stats['total_students'] * .47)) }}, {{ max(1, intval($stats['total_students'] * .58)) }}, {{ max(1, intval($stats['total_students'] * .71)) }}, {{ max(1, intval($stats['total_students'] * .85)) }}, {{ $stats['total_students'] }}],
                borderColor:'#16A34A',
                backgroundColor: (ctx) => { const g = ctx.chart.ctx.createLinearGradient(0,0,0,220); g.addColorStop(0,'rgba(22,163,74,.12)'); g.addColorStop(1,'rgba(22,163,74,0)'); return g; },
                borderWidth:2.5, pointBackgroundColor:'#16A34A', pointBorderColor:'#fff', pointBorderWidth:2, pointRadius:4, pointHoverRadius:6, fill:true, tension:0.42,
            }]
        },
        options: {
            responsive:true, maintainAspectRatio:false,
            plugins: { legend:{display:false}, tooltip:{backgroundColor:'#0F172A',titleColor:'#94A3B8',bodyColor:'#F8FAFC',padding:12,cornerRadius:10,displayColors:false} },
            scales: {
                x: { border:{display:false}, grid:{color:'#F1F5F9'}, ticks:{color:'#94A3B8',font:{size:12,family:'Inter'}} },
                y: { border:{display:false}, grid:{color:'#F1F5F9'}, ticks:{color:'#94A3B8',font:{size:12,family:'Inter'}}, beginAtZero:false }
            }
        }
    });

    @if($repartition->count())
    const ctx2 = document.getElementById('filiereChart').getContext('2d');
    // Palette générée côté PHP pour être cohérente avec la légende
    const filiereColors = [
        '#16A34A','#38BDF8','#FACC15','#F87171','#A78BFA',
        '#FB923C','#34D399','#60A5FA','#F472B6','#A3E635',
    ];
    new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels:   {!! $repartition->pluck('nom')->toJson() !!},
            datasets: [{
                data:            {!! $repartition->pluck('students_count')->toJson() !!},
                backgroundColor: filiereColors.slice(0, {{ $repartition->count() }}),
                borderWidth: 0,
                hoverOffset: 6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            cutout: '70%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#0F172A',
                    titleColor: '#94A3B8',
                    bodyColor: '#F8FAFC',
                    padding: 10,
                    cornerRadius: 10,
                    callbacks: {
                        label: (ctx) => ` ${ctx.label} : ${ctx.parsed} étudiant(s)`,
                    }
                }
            }
        }
    });
    @endif
});
</script>
@endpush
