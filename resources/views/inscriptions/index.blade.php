@extends('layouts.admin')
@section('title', 'Inscriptions — Campus Manager')
@section('nav_inscriptions', 'active')

@push('styles')
<style>
    .kpi-mini { background:#FFFFFF; border-radius:16px; padding:16px 20px; border:1px solid #F1F5F9; box-shadow:0 2px 8px rgba(15,23,42,.04); }
    .tab-btn { height:36px; padding:0 16px; border-radius:9px; font-size:13px; font-weight:500; border:none; cursor:pointer; transition:background .15s,color .15s; font-family:'Inter',sans-serif; white-space:nowrap; }
    .tab-btn.active  { background:#DCFCE7; color:#15803D; font-weight:600; }
    .tab-btn:not(.active) { background:transparent; color:#64748B; }
    .tab-btn:not(.active):hover { background:#F1F5F9; color:#0F172A; }
    .tbl-row { display:grid; grid-template-columns:2fr 1.2fr 80px 100px 120px 90px 90px; align-items:center; padding:0 20px; min-height:72px; border-bottom:1px solid #F1F5F9; transition:background .12s; gap:0; }
    .tbl-row:last-child { border-bottom:none; }
    .tbl-row:hover { background:#F8FAFC; }
    .tbl-header { background:#FAFBFC; min-height:48px !important; }
    .tbl-cell { min-width:0; overflow:hidden; }
    .badge { display:inline-flex; align-items:center; height:26px; padding:0 10px; border-radius:999px; font-size:11px; font-weight:600; white-space:nowrap; }
    .act-btn { width:30px; height:30px; border-radius:8px; border:none; background:transparent; display:inline-flex; align-items:center; justify-content:center; cursor:pointer; transition:background .12s; }
    .act-btn:hover { background:#F1F5F9; }
    .f-label { font-size:11px; font-weight:600; color:#94A3B8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:6px; display:block; }
    .f-input { width:100%; height:42px; border:1.5px solid #E2E8F0; border-radius:10px; padding:0 12px; font-size:13px; color:#0F172A; outline:none; font-family:'Inter',sans-serif; transition:border-color .15s,box-shadow .15s; background:#FFF; }
    .f-input:focus { border-color:#22C55E; box-shadow:0 0 0 3px rgba(34,197,94,.08); }
    .f-select { width:100%; height:42px; border:1.5px solid #E2E8F0; border-radius:10px; padding:0 36px 0 12px; font-size:13px; color:#0F172A; outline:none; appearance:none; font-family:'Inter',sans-serif; background:#FFF url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%2394A3B8' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E") no-repeat right 12px center; cursor:pointer; }
    .f-select:focus { border-color:#22C55E; outline:none; box-shadow:0 0 0 3px rgba(34,197,94,.08); }
    .f-textarea { width:100%; border:1.5px solid #E2E8F0; border-radius:10px; padding:10px 12px; font-size:13px; color:#0F172A; outline:none; font-family:'Inter',sans-serif; resize:vertical; min-height:70px; transition:border-color .15s; background:#FFF; }
    .f-textarea:focus { border-color:#22C55E; box-shadow:0 0 0 3px rgba(34,197,94,.08); }
    .panel-section { background:#F8FAFC; border-radius:14px; padding:14px; margin-bottom:12px; }
    .panel-section-title { font-size:11px; font-weight:700; color:#0F172A; text-transform:uppercase; letter-spacing:.5px; margin-bottom:14px; display:flex; align-items:center; gap:7px; }
    .toast-container { position:fixed; bottom:28px; right:28px; z-index:9999; display:flex; flex-direction:column; gap:10px; }
    .toast { display:flex; align-items:center; gap:12px; padding:14px 18px; border-radius:14px; box-shadow:0 8px 24px rgba(15,23,42,.14); min-width:300px; max-width:400px; animation:slideIn .3s ease; background:#FFFFFF; }
    .toast.success { border-left:4px solid #16A34A; } .toast.error { border-left:4px solid #EF4444; }
    @keyframes slideIn  { from { transform:translateX(120%); opacity:0; } to { transform:translateX(0); opacity:1; } }
    @keyframes slideOut { from { transform:translateX(0); opacity:1; } to { transform:translateX(120%); opacity:0; } }
    .modal-overlay { position:fixed; inset:0; background:rgba(15,23,42,.45); z-index:8888; display:flex; align-items:center; justify-content:center; backdrop-filter:blur(3px); }
    .modal-box { background:#FFFFFF; border-radius:20px; padding:32px; max-width:440px; width:90%; box-shadow:0 20px 60px rgba(15,23,42,.2); }
    .empty-state { display:flex; flex-direction:column; align-items:center; justify-content:center; padding:60px 20px; text-align:center; }
</style>
@endpush

@section('content')

<div class="toast-container" id="toast-container"></div>

<div class="modal-overlay" id="delete-modal" style="display:none;">
    <div class="modal-box">
        <div style="width:52px;height:52px;border-radius:14px;background:#FEF2F2;display:flex;align-items:center;justify-content:center;margin-bottom:20px;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#EF4444" stroke-width="2" stroke-linecap="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
        </div>
        <h3 style="font-size:18px;font-weight:700;color:#0F172A;margin:0 0 8px;">Supprimer cette inscription ?</h3>
        <p style="font-size:14px;color:#64748B;line-height:1.6;margin:0 0 8px;">Étudiant : <strong id="delete-name" style="color:#0F172A;"></strong></p>
        <p style="font-size:13px;color:#EF4444;background:#FEF2F2;border-radius:8px;padding:10px 12px;margin:0 0 24px;">⚠ Cette action est irréversible.</p>
        <div style="display:flex;gap:10px;">
            <button id="delete-cancel" style="flex:1;height:44px;border-radius:12px;border:1px solid #E2E8F0;background:#fff;font-size:14px;font-weight:600;color:#475569;cursor:pointer;font-family:'Inter',sans-serif;">Annuler</button>
            <button id="delete-confirm" style="flex:1.5;height:44px;border-radius:12px;border:none;background:#EF4444;font-size:14px;font-weight:600;color:#fff;cursor:pointer;font-family:'Inter',sans-serif;">Supprimer</button>
        </div>
    </div>
</div>

<div style="display:flex; height:100%; min-height:0; flex:1;">

    <div style="flex:1; min-width:0; overflow-y:auto; display:flex; flex-direction:column;">

        <!-- Topbar -->
        <div style="padding:22px 24px 0; display:flex; justify-content:space-between; align-items:flex-start; gap:16px; flex-wrap:wrap; flex-shrink:0;">
            <div>
                <h1 style="font-size:24px; font-weight:700; color:#0F172A; margin:0; line-height:1.2;">Inscriptions</h1>
                <p style="font-size:13px; color:#64748B; margin:4px 0 0;">Gérez toutes les inscriptions de votre université</p>
            </div>
            <div style="display:flex; align-items:center; gap:10px; flex-wrap:wrap;">
                <form method="GET" action="{{ route('inscriptions.index') }}" id="search-form" style="display:contents;">
                    <input type="hidden" name="statut" value="{{ request('statut','toutes') }}">
                    <input type="hidden" name="annee" value="{{ request('annee','toutes') }}">
                    <div style="height:42px;background:#fff;border:1px solid #E2E8F0;border-radius:11px;display:flex;align-items:center;padding:0 13px;gap:8px;min-width:200px;">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#94A3B8" stroke-width="2" stroke-linecap="round" style="flex-shrink:0;"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        <input type="text" name="q" value="{{ request('q') }}" id="search-input" placeholder="Rechercher un étudiant…" style="border:none;outline:none;font-size:13px;color:#0F172A;background:transparent;flex:1;font-family:'Inter',sans-serif;min-width:0;">
                    </div>
                    <!-- Filtre année -->
                    <select name="annee" onchange="document.getElementById('search-form').submit()" style="height:42px;border:1px solid #E2E8F0;border-radius:11px;padding:0 32px 0 12px;font-size:13px;font-family:'Inter',sans-serif;color:#475569;background:#fff url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%2394A3B8' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E\") no-repeat right 10px center;appearance:none;outline:none;cursor:pointer;">
                        <option value="toutes">Toutes les années</option>
                        @foreach($annees as $a)
                        <option value="{{ $a }}" {{ request('annee') === $a ? 'selected' : '' }}>{{ $a }}</option>
                        @endforeach
                    </select>
                </form>
                <button id="btn-add" style="height:42px;padding:0 18px;background:#16A34A;border:none;border-radius:11px;display:flex;align-items:center;gap:7px;cursor:pointer;font-size:13px;font-weight:600;color:#FFF;box-shadow:0 4px 14px rgba(22,163,74,.22);font-family:'Inter',sans-serif;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Nouvelle inscription
                </button>
            </div>
        </div>

        <!-- KPI -->
        <div style="padding:14px 24px 0; display:grid; grid-template-columns:repeat(4,1fr); gap:12px; flex-shrink:0;">
            @foreach([
                ['total',      'Total',       '#16A34A','#DCFCE7',$kpis['total']],
                ['en_attente', 'En attente',  '#D97706','#FEF3C7',$kpis['en_attente']],
                ['validees',   'Validées',    '#3B82F6','#DBEAFE',$kpis['validees']],
                ['refusees',   'Refusées',    '#EF4444','#FEE2E2',$kpis['refusees']],
            ] as [$key,$l,$c,$bg,$v])
            <div class="kpi-mini">
                <div id="kpi-{{ $key }}" style="font-size:24px; font-weight:700; color:#0F172A; line-height:1;">{{ number_format($v) }}</div>
                <div style="font-size:12px; color:#64748B; margin-top:4px; display:flex; align-items:center; gap:5px;">
                    <div style="width:7px; height:7px; border-radius:50%; background:{{ $c }};"></div>{{ $l }}
                </div>
            </div>
            @endforeach
        </div>

        <!-- Tabs statut -->
        <div style="padding:12px 24px 0; flex-shrink:0;">
            <div style="display:inline-flex; gap:4px; background:#F8FAFC; padding:4px; border-radius:12px;">
                @foreach(['toutes' => 'Toutes', 'en_attente' => 'En attente', 'validee' => 'Validées', 'refusee' => 'Refusées'] as $val => $label)
                <a href="{{ route('inscriptions.index', array_merge(request()->query(), ['statut' => $val, 'page' => 1])) }}"
                   class="tab-btn {{ request('statut','toutes') === $val ? 'active' : '' }}" style="text-decoration:none;display:inline-flex;align-items:center;">
                    {{ $label }}
                </a>
                @endforeach
            </div>
        </div>

        <!-- Table -->
        <div style="margin:12px 24px 0; background:#FFFFFF; border-radius:18px; border:1px solid #F1F5F9; box-shadow:0 2px 10px rgba(15,23,42,.04); overflow:hidden; flex-shrink:0;">
            <div class="tbl-row tbl-header" style="border-bottom:1px solid #F1F5F9;">
                @foreach(['Étudiant','Filière','Niveau','Année','Date','Statut','Actions'] as $col)
                <div style="font-size:10px; font-weight:700; letter-spacing:.6px; text-transform:uppercase; color:#94A3B8;">{{ $col }}</div>
                @endforeach
            </div>

            @forelse($inscriptions as $insc)
            @php [$sBg,$sFg] = $insc->statut_colors; $colors=['#BBF7D0','#BAE6FD','#FDE68A','#FBCFE8','#DDD6FE','#FED7AA']; @endphp
            <div class="tbl-row" id="insc-row-{{ $insc->id }}">
                <div class="tbl-cell" style="display:flex; align-items:center; gap:11px;">
                    <div style="width:36px; height:36px; border-radius:50%; background:{{ $colors[$loop->index % 6] }}; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:13px; color:#0F172A; flex-shrink:0;">
                        {{ $insc->student?->initiale ?? '?' }}
                    </div>
                    <div style="min-width:0;">
                        <div style="font-size:13px; font-weight:600; color:#0F172A; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $insc->student?->nom_complet ?? '—' }}</div>
                        <div style="font-size:11px; color:#94A3B8; margin-top:1px; font-family:monospace;">{{ $insc->student?->matricule ?? '' }}</div>
                    </div>
                </div>
                <div class="tbl-cell" style="font-size:13px; color:#0F172A; font-weight:500; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $insc->filiere?->nom ?? '—' }}</div>
                <div class="tbl-cell"><span style="font-size:12px; font-weight:600; background:#EFF6FF; color:#1D4ED8; padding:3px 9px; border-radius:999px;">{{ $insc->niveau }}</span></div>
                <div class="tbl-cell" style="font-size:12px; color:#64748B;">{{ $insc->annee_academique }}</div>
                <div class="tbl-cell" style="font-size:12px; color:#64748B;">{{ $insc->date_inscription?->format('d/m/Y') ?? '—' }}</div>
                <div class="tbl-cell"><span style="background:{{ $sBg }}; color:{{ $sFg }}; padding:3px 10px; border-radius:999px; font-size:11px; font-weight:600;">{{ $insc->statut_label }}</span></div>
                <div class="tbl-cell" style="display:flex; gap:2px;">
                    <button class="act-btn btn-edit"
                        data-id="{{ $insc->id }}"
                        data-student="{{ $insc->student_id }}" data-filiere="{{ $insc->filiere_id }}"
                        data-niveau="{{ $insc->niveau }}" data-annee="{{ $insc->annee_academique }}"
                        data-statut="{{ $insc->statut }}" data-date="{{ $insc->date_inscription?->format('Y-m-d') }}"
                        data-notes="{{ $insc->notes }}"
                        title="Modifier">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#475569" stroke-width="2" stroke-linecap="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    </button>
                    <button class="act-btn btn-delete" data-id="{{ $insc->id }}" data-name="{{ $insc->student?->nom_complet ?? 'Étudiant' }}" title="Supprimer">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#EF4444" stroke-width="2" stroke-linecap="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                    </button>
                </div>
            </div>
            @empty
            <div class="empty-state">
                <div style="width:60px;height:60px;border-radius:16px;background:#F1F5F9;display:flex;align-items:center;justify-content:center;margin-bottom:14px;">
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#94A3B8" stroke-width="1.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                </div>
                <h3 style="font-size:16px;font-weight:700;color:#0F172A;margin:0 0 6px;">Aucune inscription trouvée</h3>
                <p style="font-size:14px;color:#64748B;margin:0 0 20px;">{{ request('q') ? 'Aucun résultat pour "'.request('q').'".' : 'Commencez par créer une inscription.' }}</p>
                <button id="btn-add-empty" style="height:40px;padding:0 20px;background:#16A34A;border:none;border-radius:10px;font-size:13px;font-weight:600;color:#fff;cursor:pointer;font-family:'Inter',sans-serif;">+ Nouvelle inscription</button>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div style="padding:14px 24px 24px; display:flex; justify-content:space-between; align-items:center; flex-shrink:0;">
            <div style="font-size:13px; color:#64748B;"><strong>{{ $inscriptions->total() }}</strong> inscription(s)</div>
            <div>{{ $inscriptions->links('pagination::simple-tailwind') }}</div>
        </div>
    </div>

    <!-- SIDE PANEL -->
    <div id="add-panel" style="width:340px; flex-shrink:0; border-left:1px solid #EAEFF5; background:#FFFFFF; overflow-y:auto; display:none; flex-direction:column;">
        <div style="padding:18px 20px 14px; display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid #F1F5F9; flex-shrink:0; position:sticky; top:0; background:#fff; z-index:10;">
            <div>
                <h2 id="panel-title" style="font-size:15px; font-weight:700; color:#0F172A; margin:0;">Nouvelle inscription</h2>
                <p style="font-size:11px; color:#94A3B8; margin:3px 0 0;">Associez un étudiant à une filière</p>
            </div>
            <button id="btn-close" style="width:28px;height:28px;border-radius:7px;border:1px solid #E2E8F0;background:#fff;display:flex;align-items:center;justify-content:center;cursor:pointer;">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#475569" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>

        <form id="insc-form" style="flex:1; overflow-y:auto; padding:16px 20px;">
            @csrf
            <input type="hidden" id="form-method" name="_method" value="POST">
            <input type="hidden" id="form-id" value="">

            <div class="panel-section">
                <div class="panel-section-title">
                    <div style="width:22px;height:22px;border-radius:6px;background:#DCFCE7;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#16A34A" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    </div>
                    Détails de l'inscription
                </div>
                <div style="display:flex; flex-direction:column; gap:10px;">
                    <div>
                        <label class="f-label">Étudiant *</label>
                        <select name="student_id" id="f-student" class="f-select">
                            <option value="">— Sélectionner un étudiant</option>
                            @foreach($students as $s)
                            <option value="{{ $s->id }}">{{ $s->prenom }} {{ $s->nom }} ({{ $s->matricule }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="f-label">Filière *</label>
                        <select name="filiere_id" id="f-filiere" class="f-select">
                            <option value="">— Sélectionner une filière</option>
                            @foreach($filieres as $f)
                            <option value="{{ $f->id }}">{{ $f->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px;">
                        <div>
                            <label class="f-label">Niveau *</label>
                            <select name="niveau" id="f-niveau" class="f-select">
                                @foreach(['L1','L2','L3','M1','M2'] as $n)
                                <option value="{{ $n }}">{{ $n }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="f-label">Année *</label>
                            <select name="annee_academique" id="f-annee" class="f-select">
                                @foreach(['2024-2025','2023-2024','2025-2026'] as $a)
                                <option value="{{ $a }}">{{ $a }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="f-label">Statut *</label>
                        <select name="statut" id="f-statut" class="f-select">
                            <option value="en_attente">En attente</option>
                            <option value="validee">Validée</option>
                            <option value="refusee">Refusée</option>
                        </select>
                    </div>
                    <div><label class="f-label">Date d'inscription</label><input type="date" name="date_inscription" id="f-date" class="f-input" value="{{ now()->format('Y-m-d') }}" style="font-size:12px;"></div>
                    <div><label class="f-label">Notes</label><textarea name="notes" id="f-notes" class="f-textarea" placeholder="Observations, commentaires…"></textarea></div>
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
(function () {
    const panel=document.getElementById('add-panel'), csrf='{{ csrf_token() }}';
    const btnSave=document.getElementById('btn-save'), form=document.getElementById('insc-form');

    function showPanel(mode='add') {
        panel.style.display='flex'; panel.style.flexDirection='column';
        document.getElementById('panel-title').textContent = mode==='add' ? 'Nouvelle inscription' : 'Modifier l\'inscription';
        if (mode==='add') {
            document.getElementById('form-method').value='POST';
            document.getElementById('form-id').value='';
            document.getElementById('f-student').value='';
            document.getElementById('f-filiere').value='';
            document.getElementById('f-niveau').value='L1';
            document.getElementById('f-annee').value='2024-2025';
            document.getElementById('f-statut').value='en_attente';
            document.getElementById('f-date').value='{{ now()->format("Y-m-d") }}';
            document.getElementById('f-notes').value='';
        }
    }
    function hidePanel() { panel.style.display='none'; }

    document.getElementById('btn-add')?.addEventListener('click', ()=>showPanel('add'));
    document.getElementById('btn-add-empty')?.addEventListener('click', ()=>showPanel('add'));
    document.getElementById('btn-close')?.addEventListener('click', hidePanel);
    document.getElementById('btn-cancel')?.addEventListener('click', hidePanel);

    document.querySelectorAll('.btn-edit').forEach(btn => {
        btn.addEventListener('click', () => {
            showPanel('edit');
            const d=btn.dataset;
            document.getElementById('form-method').value='PUT';
            document.getElementById('form-id').value=d.id;
            document.getElementById('f-student').value=d.student;
            document.getElementById('f-filiere').value=d.filiere;
            document.getElementById('f-niveau').value=d.niveau;
            document.getElementById('f-annee').value=d.annee;
            document.getElementById('f-statut').value=d.statut;
            document.getElementById('f-date').value=d.date||'';
            document.getElementById('f-notes').value=d.notes||'';
        });
    });

    btnSave.addEventListener('click', async () => {
        const id=document.getElementById('form-id').value, method=document.getElementById('form-method').value;
        const url=id?`/inscriptions/${id}`:'/inscriptions';
        const data=new FormData(form); data.set('_method',method);
        btnSave.disabled=true;
        document.getElementById('save-spinner').style.display='inline-block';
        document.getElementById('save-text').textContent='Enregistrement…';
        try {
            const res=await fetch(url,{method:'POST',headers:{'X-CSRF-TOKEN':csrf,'Accept':'application/json'},body:data});
            const json=await res.json();
            if(json.success){
                showToast(json.message,'success');
                hidePanel();
                if(json.kpis){const m={total:'kpi-total',en_attente:'kpi-en_attente',validees:'kpi-validees',refusees:'kpi-refusees'};Object.entries(m).forEach(([k,id])=>{const el=document.getElementById(id);if(el&&json.kpis[k]!==undefined)el.textContent=Number(json.kpis[k]).toLocaleString('fr-FR');});}
                setTimeout(()=>location.reload(),1200);
            }
            else showToast(json.message||'Erreur.','error');
        } catch { showToast('Erreur réseau.','error'); }
        finally { btnSave.disabled=false; document.getElementById('save-spinner').style.display='none'; document.getElementById('save-text').textContent='Enregistrer'; }
    });

    // Delete
    const deleteModal=document.getElementById('delete-modal'), deleteConfirm=document.getElementById('delete-confirm');
    let pendingId=null;
    document.querySelectorAll('.btn-delete').forEach(btn=>{
        btn.addEventListener('click',()=>{pendingId=btn.dataset.id;document.getElementById('delete-name').textContent=btn.dataset.name;deleteModal.style.display='flex';});
    });
    document.getElementById('delete-cancel').addEventListener('click',()=>{deleteModal.style.display='none';pendingId=null;});
    deleteModal.addEventListener('click',e=>{if(e.target===deleteModal){deleteModal.style.display='none';pendingId=null;}});
    deleteConfirm.addEventListener('click',async()=>{
        if(!pendingId)return; deleteConfirm.disabled=true; deleteConfirm.textContent='Suppression…';
        try {
            const res=await fetch(`/inscriptions/${pendingId}`,{method:'POST',headers:{'X-CSRF-TOKEN':csrf,'Accept':'application/json','Content-Type':'application/x-www-form-urlencoded'},body:'_method=DELETE'});
            const json=await res.json();
            if(json.success){
                deleteModal.style.display='none';
                const row=document.getElementById(`insc-row-${pendingId}`);
                if(row){row.style.transition='opacity .3s ease';row.style.opacity='0';setTimeout(()=>row.remove(),300);}
                showToast(json.message,'success');
                if(json.kpis){const m={total:'kpi-total',en_attente:'kpi-en_attente',validees:'kpi-validees',refusees:'kpi-refusees'};Object.entries(m).forEach(([k,id])=>{const el=document.getElementById(id);if(el&&json.kpis[k]!==undefined)el.textContent=Number(json.kpis[k]).toLocaleString('fr-FR');});}
            } else showToast(json.message||'Erreur.','error');
        } catch{showToast('Erreur réseau.','error');}
        finally{deleteConfirm.disabled=false;deleteConfirm.textContent='Supprimer';pendingId=null;}
    });

    // Search
    const si=document.getElementById('search-input'), sf=document.getElementById('search-form'); let t;
    si?.addEventListener('input',()=>{clearTimeout(t);t=setTimeout(()=>sf.submit(),500);});

    function showToast(message,type='success'){
        const c=document.getElementById('toast-container'),el=document.createElement('div');
        el.className=`toast ${type}`;
        el.innerHTML=`<div style="width:30px;height:30px;border-radius:8px;background:${type==='success'?'#DCFCE7':'#FEE2E2'};display:flex;align-items:center;justify-content:center;flex-shrink:0;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="${type==='success'?'#16A34A':'#EF4444'}" stroke-width="2.5" stroke-linecap="round">${type==='success'?'<polyline points="20 6 9 17 4 12"/>':'<line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>'}</svg></div><div style="flex:1;min-width:0;"><div style="font-size:13px;font-weight:600;color:#0F172A;">${type==='success'?'Succès':'Erreur'}</div><div style="font-size:12px;color:#64748B;margin-top:2px;line-height:1.4;">${message}</div></div><button onclick="this.parentElement.remove()" style="background:none;border:none;cursor:pointer;color:#94A3B8;font-size:16px;flex-shrink:0;padding:0;">×</button>`;
        c.appendChild(el);
        setTimeout(()=>{el.style.animation='slideOut .3s ease forwards';setTimeout(()=>el.remove(),300);},4000);
    }
})();
</script>
@endpush
