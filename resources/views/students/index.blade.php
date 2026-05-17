@extends('layouts.admin')

@section('title', 'Gestion des étudiants — Campus Manager')
@section('nav_students', 'active')

@push('styles')
<style>
    .tab-btn { height:36px; padding:0 16px; border-radius:9px; font-size:13px; font-weight:500; border:none; cursor:pointer; transition:background .15s,color .15s; font-family:'Inter',sans-serif; white-space:nowrap; }
    .tab-btn.active { background:#DCFCE7; color:#15803D; font-weight:600; }
    .tab-btn:not(.active) { background:transparent; color:#64748B; }
    .tab-btn:not(.active):hover { background:#F1F5F9; color:#0F172A; }

    .tbl-grid { display:grid; grid-template-columns:220px 110px 120px 90px 1fr 130px 80px 100px; align-items:center; padding:0 20px; min-width:0; }
    .tbl-header { height:48px; background:#FAFBFC; border-bottom:1px solid #F1F5F9; }
    .tbl-row { height:72px; border-bottom:1px solid #F1F5F9; transition:background .12s; }
    .tbl-row:last-child { border-bottom:none; }
    .tbl-row:hover { background:#F8FAFC; }
    .tbl-cell { min-width:0; overflow:hidden; }

    .act-btn { width:30px; height:30px; border-radius:8px; border:none; background:transparent; display:inline-flex; align-items:center; justify-content:center; cursor:pointer; transition:background .12s; }
    .act-btn:hover { background:#F1F5F9; }
    .badge { display:inline-flex; align-items:center; height:26px; padding:0 10px; border-radius:999px; font-size:11px; font-weight:600; white-space:nowrap; }

    .kpi-mini { background:#FFFFFF; border-radius:16px; padding:16px 20px; border:1px solid #F1F5F9; box-shadow:0 2px 8px rgba(15,23,42,.04); }

    /* Panel form */
    .f-label { font-size:11px; font-weight:600; color:#94A3B8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:6px; display:block; }
    .f-input { width:100%; height:42px; border:1.5px solid #E2E8F0; border-radius:10px; padding:0 12px; font-size:13px; color:#0F172A; outline:none; font-family:'Inter',sans-serif; transition:border-color .15s,box-shadow .15s; background:#FFF; }
    .f-input:focus { border-color:#22C55E; box-shadow:0 0 0 3px rgba(34,197,94,.08); }
    .f-input.error { border-color:#EF4444; }
    .f-select { width:100%; height:42px; border:1.5px solid #E2E8F0; border-radius:10px; padding:0 36px 0 12px; font-size:13px; color:#0F172A; outline:none; appearance:none; font-family:'Inter',sans-serif; background:#FFF url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%2394A3B8' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E") no-repeat right 12px center; cursor:pointer; transition:border-color .15s; }
    .f-select:focus { border-color:#22C55E; outline:none; box-shadow:0 0 0 3px rgba(34,197,94,.08); }
    .panel-section { background:#F8FAFC; border-radius:14px; padding:14px; margin-bottom:12px; }
    .panel-section-title { font-size:11px; font-weight:700; color:#0F172A; text-transform:uppercase; letter-spacing:.5px; margin-bottom:14px; display:flex; align-items:center; gap:7px; }

    /* Toast notifications */
    .toast-container { position:fixed; bottom:28px; right:28px; z-index:9999; display:flex; flex-direction:column; gap:10px; }
    .toast { display:flex; align-items:center; gap:12px; padding:14px 18px; border-radius:14px; box-shadow:0 8px 24px rgba(15,23,42,.14); min-width:300px; max-width:400px; animation:slideIn .3s ease; }
    .toast.success { background:#FFFFFF; border-left:4px solid #16A34A; }
    .toast.error   { background:#FFFFFF; border-left:4px solid #EF4444; }
    @keyframes slideIn { from { transform:translateX(120%); opacity:0; } to { transform:translateX(0); opacity:1; } }
    @keyframes slideOut { from { transform:translateX(0); opacity:1; } to { transform:translateX(120%); opacity:0; } }

    /* Delete modal */
    .modal-overlay { position:fixed; inset:0; background:rgba(15,23,42,.45); z-index:8888; display:flex; align-items:center; justify-content:center; backdrop-filter:blur(3px); }
    .modal-box { background:#FFFFFF; border-radius:20px; padding:32px; max-width:440px; width:90%; box-shadow:0 20px 60px rgba(15,23,42,.2); }

    /* Empty state */
    .empty-state { display:flex; flex-direction:column; align-items:center; justify-content:center; padding:60px 20px; text-align:center; }
</style>
@endpush

@section('content')

<!-- Toast container -->
<div class="toast-container" id="toast-container"></div>

<!-- Delete confirmation modal -->
<div class="modal-overlay" id="delete-modal" style="display:none;">
    <div class="modal-box">
        <div style="width:52px; height:52px; border-radius:14px; background:#FEF2F2; display:flex; align-items:center; justify-content:center; margin-bottom:20px;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#EF4444" stroke-width="2" stroke-linecap="round">
                <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                <path d="M10 11v6M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
            </svg>
        </div>
        <h3 style="font-size:18px; font-weight:700; color:#0F172A; margin:0 0 8px;">Supprimer cet étudiant ?</h3>
        <p style="font-size:14px; color:#64748B; line-height:1.6; margin:0 0 8px;">
            Vous êtes sur le point de supprimer <strong id="delete-student-name" style="color:#0F172A;"></strong>.
        </p>
        <p style="font-size:13px; color:#EF4444; background:#FEF2F2; border-radius:8px; padding:10px 12px; margin:0 0 24px;">
            ⚠ Cette action est irréversible. Toutes les données seront définitivement supprimées.
        </p>
        <div style="display:flex; gap:10px;">
            <button id="delete-cancel" style="flex:1; height:44px; border-radius:12px; border:1px solid #E2E8F0; background:#fff; font-size:14px; font-weight:600; color:#475569; cursor:pointer; font-family:'Inter',sans-serif;">
                Annuler
            </button>
            <button id="delete-confirm" style="flex:1.5; height:44px; border-radius:12px; border:none; background:#EF4444; font-size:14px; font-weight:600; color:#fff; cursor:pointer; font-family:'Inter',sans-serif; box-shadow:0 4px 12px rgba(239,68,68,.25);">
                Supprimer définitivement
            </button>
        </div>
    </div>
</div>

<div style="display:flex; height:100%; min-height:0; flex:1;">

    <!-- ══ TABLE AREA ══ -->
    <div style="flex:1; min-width:0; overflow-y:auto; display:flex; flex-direction:column;">

        <!-- Topbar -->
        <div style="padding:22px 24px 0; display:flex; justify-content:space-between; align-items:flex-start; gap:16px; flex-wrap:wrap; flex-shrink:0;">
            <div>
                <h1 style="font-size:24px; font-weight:700; color:#0F172A; line-height:1.2; margin:0;">Gestion des étudiants</h1>
                <p style="font-size:13px; color:#64748B; margin:4px 0 0;">Gérez et suivez les étudiants de votre université</p>
            </div>
            <div style="display:flex; align-items:center; gap:10px; flex-wrap:wrap;">
                <form method="GET" action="{{ route('students.index') }}" style="display:contents;" id="search-form">
                    <input type="hidden" name="statut" value="{{ request('statut','tous') }}">
                    <div style="height:42px; background:#fff; border:1px solid #E2E8F0; border-radius:11px; display:flex; align-items:center; padding:0 13px; gap:8px; min-width:220px; max-width:260px;">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#94A3B8" stroke-width="2" stroke-linecap="round" style="flex-shrink:0;"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Nom, matricule, email…" id="search-input" style="border:none;outline:none;font-size:13px;color:#0F172A;background:transparent;flex:1;font-family:'Inter',sans-serif;min-width:0;">
                    </div>
                </form>
                <button id="btn-add" style="height:42px; padding:0 18px; background:#16A34A; border:none; border-radius:11px; display:flex; align-items:center; gap:7px; cursor:pointer; font-size:13px; font-weight:600; color:#FFF; box-shadow:0 4px 14px rgba(22,163,74,.22); font-family:'Inter',sans-serif; transition:transform .2s,box-shadow .2s;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Ajouter un étudiant
                </button>
            </div>
        </div>

        <!-- KPI mini — IDs pour mise à jour temps réel -->
        <div style="padding:14px 24px 0; display:grid; grid-template-columns:repeat(4,1fr); gap:12px; flex-shrink:0;">
            @foreach([['total','Total','#16A34A','#DCFCE7',$kpis['total']],['actifs','Actifs','#3B82F6','#DBEAFE',$kpis['actifs']],['nouveaux','Année en cours','#F97316','#FED7AA',$kpis['nouveaux']],['diplomes','Diplômés','#8B5CF6','#DDD6FE',$kpis['diplomes']]] as [$key,$l,$c,$bg,$v])
            <div class="kpi-mini">
                <div id="kpi-{{ $key }}" style="font-size:22px; font-weight:700; color:#0F172A; line-height:1;">{{ number_format($v) }}</div>
                <div style="font-size:12px; color:#64748B; margin-top:4px; display:flex; align-items:center; gap:5px;">
                    <div style="width:7px; height:7px; border-radius:50%; background:{{ $c }};"></div>{{ $l }}
                </div>
            </div>
            @endforeach
        </div>

        <!-- Tabs -->
        <div style="padding:12px 24px 0; flex-shrink:0;">
            <div style="display:inline-flex; gap:4px; background:#F8FAFC; padding:4px; border-radius:12px;">
                @foreach(['tous' => 'Tous les étudiants', 'actif' => 'Actifs', 'inactif' => 'Inactifs', 'diplome' => 'Diplômés', 'suspendu' => 'Suspendus'] as $val => $label)
                <a href="{{ route('students.index', array_merge(request()->query(), ['statut' => $val, 'page' => 1])) }}"
                   class="tab-btn {{ request('statut', 'tous') === $val ? 'active' : '' }}" style="display:inline-flex; align-items:center; text-decoration:none;">
                    {{ $label }}
                </a>
                @endforeach
            </div>
        </div>

        <!-- Table -->
        <div style="margin:12px 24px 0; background:#FFFFFF; border-radius:18px; border:1px solid #F1F5F9; box-shadow:0 2px 10px rgba(15,23,42,.04); overflow:hidden; flex-shrink:0;">

            <div class="tbl-grid tbl-header">
                @foreach(['Étudiant','Matricule','Filière','Niveau','Email','Téléphone','Statut','Actions'] as $col)
                <div style="font-size:10px; font-weight:700; letter-spacing:.6px; text-transform:uppercase; color:#94A3B8;">{{ $col }}</div>
                @endforeach
            </div>

            @forelse($students as $student)
            @php [$sBg,$sFg] = $student->statut_colors; @endphp
            <div class="tbl-grid tbl-row" id="student-row-{{ $student->id }}">
                <div class="tbl-cell" style="display:flex; align-items:center; gap:11px;">
                    <div style="width:38px; height:38px; border-radius:50%; background:{{ ['#BBF7D0','#BAE6FD','#FDE68A','#FBCFE8','#DDD6FE','#FED7AA'][$loop->index % 6] }}; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:14px; color:#0F172A; flex-shrink:0;">
                        {{ $student->initiale }}
                    </div>
                    <div style="min-width:0;">
                        <div style="font-size:13px; font-weight:600; color:#0F172A; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $student->nom_complet }}</div>
                        <div style="font-size:11px; color:#94A3B8; margin-top:1px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $student->email }}</div>
                    </div>
                </div>
                <div class="tbl-cell" style="font-size:12px; color:#475569; font-family:monospace; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $student->matricule }}</div>
                <div class="tbl-cell" style="font-size:13px; font-weight:500; color:#0F172A; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $student->filiere?->nom ?? '—' }}</div>
                <div class="tbl-cell" style="font-size:12px; color:#475569;">{{ $student->niveau }}</div>
                <div class="tbl-cell" style="font-size:12px; color:#64748B; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $student->email }}</div>
                <div class="tbl-cell" style="font-size:12px; color:#64748B; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $student->telephone ?? '—' }}</div>
                <div class="tbl-cell">
                    <span class="badge" style="background:{{ $sBg }}; color:{{ $sFg }};">{{ $student->statut_label }}</span>
                </div>
                <div class="tbl-cell" style="display:flex; gap:2px;">
                    <button class="act-btn btn-view" data-id="{{ $student->id }}" title="Voir le profil">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#475569" stroke-width="2" stroke-linecap="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    </button>
                    <button class="act-btn btn-edit" data-id="{{ $student->id }}"
                        data-prenom="{{ $student->prenom }}" data-nom="{{ $student->nom }}" data-email="{{ $student->email }}"
                        data-telephone="{{ $student->telephone }}" data-sexe="{{ $student->sexe }}" data-dob="{{ $student->date_naissance?->format('Y-m-d') }}"
                        data-matricule="{{ $student->matricule }}" data-nationalite="{{ $student->nationalite }}"
                        data-statut="{{ $student->statut }}" data-filiere="{{ $student->filiere_id }}"
                        data-niveau="{{ $student->niveau }}" data-annee="{{ $student->annee_academique }}"
                        title="Modifier">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#475569" stroke-width="2" stroke-linecap="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    </button>
                    <button class="act-btn btn-delete" data-id="{{ $student->id }}" data-name="{{ $student->nom_complet }}" title="Supprimer">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#EF4444" stroke-width="2" stroke-linecap="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                    </button>
                </div>
            </div>
            @empty
            <div class="empty-state">
                <div style="width:64px; height:64px; border-radius:18px; background:#F1F5F9; display:flex; align-items:center; justify-content:center; margin-bottom:16px;">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#94A3B8" stroke-width="1.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                </div>
                <h3 style="font-size:16px; font-weight:700; color:#0F172A; margin:0 0 6px;">Aucun étudiant trouvé</h3>
                <p style="font-size:14px; color:#64748B; margin:0 0 20px;">{{ request('q') ? 'Aucun résultat pour "'.request('q').'". Essayez un autre terme.' : 'Commencez par ajouter votre premier étudiant.' }}</p>
                @if(request('q'))
                @else
                <button id="btn-add-empty" style="height:40px; padding:0 20px; background:#16A34A; border:none; border-radius:10px; font-size:13px; font-weight:600; color:#fff; cursor:pointer; font-family:'Inter',sans-serif;">+ Ajouter un étudiant</button>
                @endif
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div style="padding:14px 24px 24px; display:flex; justify-content:space-between; align-items:center; flex-shrink:0;">
            <div style="font-size:13px; color:#64748B;">
                Affichage de <strong>{{ $students->firstItem() ?? 0 }}–{{ $students->lastItem() ?? 0 }}</strong> sur <strong>{{ $students->total() }}</strong> étudiants
            </div>
            <div>{{ $students->links('pagination::simple-tailwind') }}</div>
        </div>
    </div>

    <!-- ══ SIDE PANEL ══ -->
    <div id="add-panel" style="width:340px; flex-shrink:0; border-left:1px solid #EAEFF5; background:#FFFFFF; overflow-y:auto; display:flex; flex-direction:column; display:none;">

        <div style="padding:18px 20px 14px; display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid #F1F5F9; flex-shrink:0; position:sticky; top:0; background:#fff; z-index:10;">
            <div>
                <h2 id="panel-title" style="font-size:15px; font-weight:700; color:#0F172A; margin:0;">Ajouter un étudiant</h2>
                <p style="font-size:11px; color:#94A3B8; margin:3px 0 0;">Remplissez les informations ci-dessous</p>
            </div>
            <button id="btn-close" style="width:28px;height:28px;border-radius:7px;border:1px solid #E2E8F0;background:#fff;display:flex;align-items:center;justify-content:center;cursor:pointer;">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#475569" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>

        <form id="student-form" style="flex:1; overflow-y:auto; padding:16px 20px;">
            @csrf
            <input type="hidden" id="form-method" name="_method" value="POST">
            <input type="hidden" id="form-student-id" value="">

            <div class="panel-section">
                <div class="panel-section-title">
                    <div style="width:22px;height:22px;border-radius:6px;background:#DCFCE7;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#16A34A" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    </div>
                    Informations personnelles
                </div>
                <div style="display:flex;flex-direction:column;gap:10px;">
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;">
                        <div><label class="f-label">Prénom *</label><input type="text" name="prenom" id="f-prenom" class="f-input" placeholder="Prénom" required></div>
                        <div><label class="f-label">Nom *</label><input type="text" name="nom" id="f-nom" class="f-input" placeholder="Nom" required></div>
                    </div>
                    <div><label class="f-label">Email *</label><input type="email" name="email" id="f-email" class="f-input" placeholder="email@exemple.com" required></div>
                    <div><label class="f-label">Téléphone</label><input type="tel" name="telephone" id="f-telephone" class="f-input" placeholder="+212 6 00 00 00 00"></div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;">
                        <div>
                            <label class="f-label">Genre *</label>
                            <select name="sexe" id="f-sexe" class="f-select">
                                <option value="M">Masculin</option>
                                <option value="F">Féminin</option>
                            </select>
                        </div>
                        <div><label class="f-label">Date naissance</label><input type="date" name="date_naissance" id="f-dob" class="f-input" style="font-size:12px;"></div>
                    </div>
                    <div><label class="f-label">Nationalité</label><input type="text" name="nationalite" id="f-nationalite" class="f-input" placeholder="Marocaine" value="Marocaine"></div>
                </div>
            </div>

            <div class="panel-section">
                <div class="panel-section-title">
                    <div style="width:22px;height:22px;border-radius:6px;background:#EFF6FF;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#3B82F6" stroke-width="2"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
                    </div>
                    Informations académiques
                </div>
                <div style="display:flex;flex-direction:column;gap:10px;">
                    <div><label class="f-label">Matricule *</label><input type="text" name="matricule" id="f-matricule" class="f-input" placeholder="INF2024001" required></div>
                    <div>
                        <label class="f-label">Filière</label>
                        <select name="filiere_id" id="f-filiere" class="f-select">
                            <option value="">— Sélectionner</option>
                            @foreach($filieres as $filiere)
                            <option value="{{ $filiere->id }}">{{ $filiere->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;">
                        <div>
                            <label class="f-label">Niveau *</label>
                            <select name="niveau" id="f-niveau" class="f-select">
                                @foreach(['L1','L2','L3','M1','M2'] as $n)
                                <option value="{{ $n }}">{{ $n }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="f-label">Année</label>
                            <select name="annee_academique" id="f-annee" class="f-select">
                                @foreach(['2024-2025','2023-2024','2022-2023'] as $a)
                                <option value="{{ $a }}">{{ $a }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="f-label">Statut *</label>
                        <select name="statut" id="f-statut" class="f-select">
                            <option value="actif">Actif</option>
                            <option value="inactif">Inactif</option>
                            <option value="suspendu">Suspendu</option>
                            <option value="diplome">Diplômé</option>
                        </select>
                    </div>
                </div>
            </div>
        </form>

        <div style="padding:12px 20px 18px; border-top:1px solid #F1F5F9; display:flex; gap:8px; flex-shrink:0; background:#fff; position:sticky; bottom:0;">
            <button id="btn-cancel" style="flex:1;height:42px;border-radius:10px;border:1px solid #E2E8F0;background:#fff;font-size:13px;font-weight:600;color:#475569;cursor:pointer;font-family:'Inter',sans-serif;">Annuler</button>
            <button id="btn-save" style="flex:2;height:42px;border-radius:10px;border:none;background:#16A34A;font-size:13px;font-weight:600;color:#FFF;cursor:pointer;font-family:'Inter',sans-serif;box-shadow:0 4px 12px rgba(22,163,74,.2);display:flex;align-items:center;justify-content:center;gap:8px;">
                <svg id="save-spinner" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" style="display:none;animation:spin .7s linear infinite;"><path d="M21 12a9 9 0 1 1-6.22-8.56"/></svg>
                <span id="save-text">Enregistrer</span>
            </button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
/* ── Helpers globaux ── */
const COLORS_S = ['#BBF7D0','#BAE6FD','#FDE68A','#FBCFE8','#DDD6FE','#FED7AA'];
const _csrf    = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

function showToast(msg, type='success') {
    const c = document.getElementById('toast-container');
    const ok = type==='success';
    const el = document.createElement('div');
    el.style.cssText=`display:flex;align-items:center;gap:12px;padding:14px 18px;border-radius:14px;box-shadow:0 8px 24px rgba(15,23,42,.14);min-width:300px;max-width:420px;background:#FFF;border-left:4px solid ${ok?'#16A34A':'#EF4444'};animation:cmSI .3s ease;`;
    el.innerHTML=`<div style="width:30px;height:30px;border-radius:8px;background:${ok?'#DCFCE7':'#FEE2E2'};display:flex;align-items:center;justify-content:center;flex-shrink:0;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="${ok?'#16A34A':'#EF4444'}" stroke-width="2.5" stroke-linecap="round">${ok?'<polyline points="20 6 9 17 4 12"/>':'<line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>'}</svg></div><div style="flex:1;min-width:0;"><div style="font-size:13px;font-weight:600;color:#0F172A;">${ok?'Succès':'Erreur'}</div><div style="font-size:12px;color:#64748B;margin-top:2px;line-height:1.4;">${msg}</div></div><button onclick="this.parentElement.remove()" style="background:none;border:none;cursor:pointer;color:#94A3B8;font-size:18px;flex-shrink:0;padding:0;line-height:1;">×</button>`;
    c?.appendChild(el);
    setTimeout(()=>{el.style.animation='cmSO .3s ease forwards';setTimeout(()=>el.remove(),300);},4500);
}
if(!document.getElementById('cm-s')){const s=document.createElement('style');s.id='cm-s';s.textContent='@keyframes cmSI{from{transform:translateX(120%);opacity:0}to{transform:translateX(0);opacity:1}}@keyframes cmSO{from{transform:translateX(0);opacity:1}to{transform:translateX(120%);opacity:0}}@keyframes cmSpin{to{transform:rotate(360deg)}}';document.head.appendChild(s);}

function updateKpis(kpis) {
    const map = {total:'kpi-total',actifs:'kpi-actifs',nouveaux:'kpi-nouveaux',diplomes:'kpi-diplomes'};
    Object.entries(map).forEach(([k,id])=>{const el=document.getElementById(id);if(el&&kpis[k]!==undefined)el.textContent=Number(kpis[k]).toLocaleString('fr-FR');});
}

/* ── Construction d'une ligne étudiant ── */
function buildStudentRow(s, idx) {
    const color = COLORS_S[idx % 6];
    return `<div class="tbl-grid tbl-row" id="student-row-${s.id}" style="animation:cmFI .3s ease;">
        <div class="tbl-cell" style="display:flex;align-items:center;gap:11px;">
            <div style="width:38px;height:38px;border-radius:50%;background:${color};display:flex;align-items:center;justify-content:center;font-weight:700;font-size:14px;color:#0F172A;flex-shrink:0;">${s.initiale}</div>
            <div style="min-width:0;"><div style="font-size:13px;font-weight:600;color:#0F172A;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${s.nom_complet}</div><div style="font-size:11px;color:#94A3B8;margin-top:1px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${s.email}</div></div>
        </div>
        <div class="tbl-cell" style="font-size:12px;color:#475569;font-family:monospace;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${s.matricule}</div>
        <div class="tbl-cell" style="font-size:13px;font-weight:500;color:#0F172A;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${s.filiere_nom}</div>
        <div class="tbl-cell" style="font-size:12px;color:#475569;">${s.niveau}</div>
        <div class="tbl-cell" style="font-size:12px;color:#64748B;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${s.email}</div>
        <div class="tbl-cell" style="font-size:12px;color:#64748B;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${s.telephone||'—'}</div>
        <div class="tbl-cell"><span class="badge" style="background:${s.statut_bg};color:${s.statut_fg};">${s.statut_label}</span></div>
        <div class="tbl-cell" style="display:flex;gap:2px;">
            <button class="act-btn btn-view" data-id="${s.id}" title="Voir"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#475569" stroke-width="2" stroke-linecap="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></button>
            <button class="act-btn btn-edit" data-id="${s.id}" data-prenom="${s.prenom}" data-nom="${s.nom}" data-email="${s.email}" data-telephone="${s.telephone||''}" data-sexe="${s.sexe}" data-dob="${s.date_naissance||''}" data-matricule="${s.matricule}" data-nationalite="${s.nationalite||''}" data-statut="${s.statut}" data-filiere="${s.filiere_id||''}" data-niveau="${s.niveau}" data-annee="${s.annee_academique}" title="Modifier"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#475569" stroke-width="2" stroke-linecap="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></button>
            <button class="act-btn btn-delete" data-id="${s.id}" data-name="${s.nom_complet}" title="Supprimer"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#EF4444" stroke-width="2" stroke-linecap="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg></button>
        </div>
    </div>`;
}

if(!document.getElementById('cm-s2')){const s2=document.createElement('style');s2.id='cm-s2';s2.textContent='@keyframes cmFI{from{opacity:0;transform:translateY(-8px)}to{opacity:1;transform:translateY(0)}}';document.head.appendChild(s2);}

/* ── Mise à jour d'une ligne existante ── */
function updateStudentRow(s) {
    const row = document.getElementById(`student-row-${s.id}`);
    if (!row) return;
    const cells = row.querySelectorAll('.tbl-cell');
    // Cell 0: nom + email (keep avatar color)
    const nameDiv = cells[0].querySelector('div:last-child');
    if (nameDiv) {
        nameDiv.children[0].textContent = s.nom_complet;
        nameDiv.children[1].textContent = s.email;
    }
    if (cells[1]) cells[1].textContent = s.matricule;
    if (cells[2]) cells[2].textContent = s.filiere_nom;
    if (cells[3]) cells[3].textContent = s.niveau;
    if (cells[4]) cells[4].textContent = s.email;
    if (cells[5]) cells[5].textContent = s.telephone || '—';
    if (cells[6]) cells[6].innerHTML = `<span class="badge" style="background:${s.statut_bg};color:${s.statut_fg};">${s.statut_label}</span>`;
    // Update edit button data attributes
    const editBtn = cells[7]?.querySelector('.btn-edit');
    if (editBtn) Object.assign(editBtn.dataset, {prenom:s.prenom,nom:s.nom,email:s.email,telephone:s.telephone||'',sexe:s.sexe,dob:s.date_naissance||'',matricule:s.matricule,nationalite:s.nationalite||'',statut:s.statut,filiere:s.filiere_id||'',niveau:s.niveau,annee:s.annee_academique});
    // Highlight
    row.style.background = '#F0FDF4';
    row.style.transition = 'background 1.4s ease';
    setTimeout(() => { row.style.background = ''; }, 1800);
}

(function () {
    const panel     = document.getElementById('add-panel');
    const btnAdd    = document.getElementById('btn-add');
    const btnAddEmpty = document.getElementById('btn-add-empty');
    const btnClose  = document.getElementById('btn-close');
    const btnCancel = document.getElementById('btn-cancel');
    const btnSave   = document.getElementById('btn-save');
    const form      = document.getElementById('student-form');
    const panelTitle= document.getElementById('panel-title');
    const methodInput = document.getElementById('form-method');
    const studentIdInput = document.getElementById('form-student-id');
    const saveSpinner = document.getElementById('save-spinner');
    const saveText    = document.getElementById('save-text');
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content
                   || '{{ csrf_token() }}';

    // ── Panel open/close ──────────────────────────
    function showPanel(mode = 'add') {
        panel.style.display = 'flex';
        panel.style.flexDirection = 'column';
        panelTitle.textContent = mode === 'add' ? 'Ajouter un étudiant' : 'Modifier l\'étudiant';
        if (mode === 'add') resetForm();
    }
    function hidePanel() { panel.style.display = 'none'; }

    function resetForm() {
        form.querySelectorAll('input,select').forEach(el => { if (el.name !== '_method') el.value = el.defaultValue || ''; });
        methodInput.value = 'POST';
        studentIdInput.value = '';
        document.getElementById('f-nationalite').value = 'Marocaine';
        document.getElementById('f-sexe').value = 'M';
        document.getElementById('f-statut').value = 'actif';
    }

    if (btnAdd)      btnAdd.addEventListener('click',      () => showPanel('add'));
    if (btnAddEmpty) btnAddEmpty.addEventListener('click', () => showPanel('add'));
    if (btnClose)    btnClose.addEventListener('click',    hidePanel);
    if (btnCancel)   btnCancel.addEventListener('click',   hidePanel);

    // ── Délégation d'événements sur la table (pour les nouvelles lignes aussi) ──
    const tableWrap = document.querySelector('[id^="student-row-"]')?.parentElement;
    document.addEventListener('click', e => {
        const editBtn = e.target.closest('.btn-edit[data-id]');
        if (editBtn && !editBtn.closest('#student-form')) {
            showPanel('edit');
            const d = editBtn.dataset;
            methodInput.value    = 'PUT';
            studentIdInput.value = d.id;
            document.getElementById('f-prenom').value      = d.prenom     || '';
            document.getElementById('f-nom').value         = d.nom        || '';
            document.getElementById('f-email').value       = d.email      || '';
            document.getElementById('f-telephone').value   = d.telephone  || '';
            document.getElementById('f-sexe').value        = d.sexe       || 'M';
            document.getElementById('f-dob').value         = d.dob        || '';
            document.getElementById('f-matricule').value   = d.matricule  || '';
            document.getElementById('f-nationalite').value = d.nationalite|| '';
            document.getElementById('f-statut').value      = d.statut     || 'actif';
            document.getElementById('f-filiere').value     = d.filiere    || '';
            document.getElementById('f-niveau').value      = d.niveau     || 'L1';
            document.getElementById('f-annee').value       = d.annee      || '2024-2025';
        }
        const delBtn = e.target.closest('.btn-delete[data-id]');
        if (delBtn) { pendingDeleteId = delBtn.dataset.id; document.getElementById('delete-student-name').textContent = delBtn.dataset.name; document.getElementById('delete-modal').style.display='flex'; }
    });

    // ── Save (AJAX + DOM temps réel) ──────────────
    btnSave.addEventListener('click', async () => {
        const id     = studentIdInput.value;
        const method = methodInput.value;
        const url    = id ? `/students/${id}` : '/students';
        const data   = new FormData(form);
        data.set('_method', method);

        btnSave.disabled = true;
        saveSpinner.style.display = 'inline-block';
        saveText.textContent = 'Enregistrement…';

        try {
            const res  = await fetch(url, { method:'POST', headers:{'X-CSRF-TOKEN':_csrf,'Accept':'application/json'}, body:data });
            const json = await res.json();

            if (json.success) {
                showToast(json.message, 'success');
                hidePanel();

                if (method === 'POST') {
                    // ── AJOUT : insérer la nouvelle ligne en haut du tableau
                    const header = document.querySelector('.tbl-header');
                    const existingRows = document.querySelectorAll('.tbl-row:not(.tbl-header)');
                    const idx = existingRows.length;
                    if (header) {
                        const tmp = document.createElement('div');
                        tmp.innerHTML = buildStudentRow(json.student, idx);
                        header.insertAdjacentElement('afterend', tmp.firstElementChild);
                    }
                } else {
                    // ── MODIFICATION : mettre à jour la ligne existante
                    updateStudentRow(json.student);
                }

                // Mettre à jour les KPIs
                if (json.kpis) updateKpis(json.kpis);

            } else {
                const msgs = json.errors ? Object.values(json.errors).flat().join(' ') : (json.message || 'Une erreur est survenue.');
                showToast(msgs, 'error');
            }
        } catch {
            showToast('Erreur réseau. Veuillez réessayer.', 'error');
        } finally {
            btnSave.disabled = false;
            saveSpinner.style.display = 'none';
            saveText.textContent = 'Enregistrer';
        }
    });

    // ── Delete modal ─────────────────────────────
    const deleteModal   = document.getElementById('delete-modal');
    const deleteConfirm = document.getElementById('delete-confirm');
    const deleteCancel  = document.getElementById('delete-cancel');
    const deleteNameEl  = document.getElementById('delete-student-name');
    let pendingDeleteId = null;

    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', () => {
            pendingDeleteId = btn.dataset.id;
            deleteNameEl.textContent = btn.dataset.name;
            deleteModal.style.display = 'flex';
        });
    });

    deleteCancel.addEventListener('click',  () => { deleteModal.style.display = 'none'; pendingDeleteId = null; });
    deleteModal.addEventListener('click', e => { if (e.target === deleteModal) { deleteModal.style.display = 'none'; pendingDeleteId = null; } });

    deleteConfirm.addEventListener('click', async () => {
        if (!pendingDeleteId) return;
        deleteConfirm.disabled = true;
        deleteConfirm.textContent = 'Suppression…';

        try {
            const res  = await fetch(`/students/${pendingDeleteId}`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': _csrf, 'Accept': 'application/json', 'Content-Type': 'application/x-www-form-urlencoded' },
                body: '_method=DELETE',
            });
            const json = await res.json();
            if (json.success) {
                deleteModal.style.display = 'none';
                // Supprimer la ligne + animation
                const row = document.getElementById(`student-row-${pendingDeleteId}`);
                if (row) { row.style.transition='opacity .3s ease'; row.style.opacity='0'; setTimeout(()=>row.remove(),300); }
                showToast(json.message, 'success');
                // Mettre à jour les KPIs immédiatement
                if (json.kpis) updateKpis(json.kpis);
            } else {
                showToast(json.message || 'Erreur lors de la suppression.', 'error');
            }
        } catch {
            showToast('Erreur réseau.', 'error');
        } finally {
            deleteConfirm.disabled = false;
            deleteConfirm.textContent = 'Supprimer définitivement';
            pendingDeleteId = null;
        }
    });

    // ── Live search (debounce) ───────────────────
    const searchInput = document.getElementById('search-input');
    const searchForm  = document.getElementById('search-form');
    let searchTimer;
    searchInput?.addEventListener('input', () => {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => searchForm.submit(), 500);
    });

    // ── Toast helper ─────────────────────────────
    function showToast(message, type = 'success') {
        const container = document.getElementById('toast-container');
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        toast.innerHTML = `
            <div style="width:32px;height:32px;border-radius:9px;background:${type==='success'?'#DCFCE7':'#FEE2E2'};display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="${type==='success'?'#16A34A':'#EF4444'}" stroke-width="2.5" stroke-linecap="round">
                    ${type==='success' ? '<polyline points="20 6 9 17 4 12"/>' : '<line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>'}
                </svg>
            </div>
            <div style="flex:1;min-width:0;">
                <div style="font-size:13px;font-weight:600;color:#0F172A;">${type==='success'?'Succès !':'Erreur'}</div>
                <div style="font-size:12px;color:#64748B;margin-top:2px;line-height:1.4;">${message}</div>
            </div>
            <button onclick="this.parentElement.remove()" style="background:none;border:none;cursor:pointer;color:#94A3B8;padding:0;font-size:16px;line-height:1;flex-shrink:0;">×</button>
        `;
        container.appendChild(toast);
        setTimeout(() => {
            toast.style.animation = 'slideOut .3s ease forwards';
            setTimeout(() => toast.remove(), 300);
        }, 4000);
    }

    // ── Add button hover ──────────────────────────
    if (btnAdd) {
        btnAdd.addEventListener('mouseenter', () => { btnAdd.style.transform='translateY(-1px)'; btnAdd.style.boxShadow='0 8px 20px rgba(22,163,74,.28)'; });
        btnAdd.addEventListener('mouseleave', () => { btnAdd.style.transform=''; btnAdd.style.boxShadow='0 4px 14px rgba(22,163,74,.22)'; });
    }
})();
</script>
@endpush
