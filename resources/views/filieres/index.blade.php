@extends('layouts.admin')
@section('title', 'Filières — Campus Manager')
@section('nav_filieres', 'active')

@push('styles')
<style>
    .card { background:#FFFFFF; border-radius:20px; padding:22px 24px; border:1px solid #F1F5F9; box-shadow:0 2px 10px rgba(15,23,42,.05); }
    .kpi-mini { background:#FFFFFF; border-radius:16px; padding:16px 20px; border:1px solid #F1F5F9; box-shadow:0 2px 8px rgba(15,23,42,.04); }
    .tab-btn { height:36px; padding:0 16px; border-radius:9px; font-size:13px; font-weight:500; border:none; cursor:pointer; transition:background .15s,color .15s; font-family:'Inter',sans-serif; white-space:nowrap; }
    .tab-btn.active  { background:#DCFCE7; color:#15803D; font-weight:600; }
    .tab-btn:not(.active) { background:transparent; color:#64748B; }
    .tab-btn:not(.active):hover { background:#F1F5F9; color:#0F172A; }
    .tbl-row { display:grid; grid-template-columns:2fr 100px 100px 100px 100px 90px 100px; align-items:center; padding:0 20px; min-height:72px; border-bottom:1px solid #F1F5F9; transition:background .12s; gap:0; }
    .tbl-row:last-child { border-bottom:none; }
    .tbl-row:hover { background:#F8FAFC; }
    .tbl-header { background:#FAFBFC; min-height:48px !important; border-bottom:1px solid #F1F5F9; }
    .tbl-cell { min-width:0; overflow:hidden; }
    .badge { display:inline-flex; align-items:center; height:26px; padding:0 10px; border-radius:999px; font-size:11px; font-weight:600; white-space:nowrap; }
    .act-btn { width:30px; height:30px; border-radius:8px; border:none; background:transparent; display:inline-flex; align-items:center; justify-content:center; cursor:pointer; transition:background .12s; }
    .act-btn:hover { background:#F1F5F9; }
    .f-label { font-size:11px; font-weight:600; color:#94A3B8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:6px; display:block; }
    .f-input { width:100%; height:42px; border:1.5px solid #E2E8F0; border-radius:10px; padding:0 12px; font-size:13px; color:#0F172A; outline:none; font-family:'Inter',sans-serif; transition:border-color .15s,box-shadow .15s; background:#FFF; }
    .f-input:focus { border-color:#22C55E; box-shadow:0 0 0 3px rgba(34,197,94,.08); }
    .f-textarea { width:100%; border:1.5px solid #E2E8F0; border-radius:10px; padding:10px 12px; font-size:13px; color:#0F172A; outline:none; font-family:'Inter',sans-serif; resize:vertical; min-height:80px; transition:border-color .15s; background:#FFF; }
    .f-textarea:focus { border-color:#22C55E; box-shadow:0 0 0 3px rgba(34,197,94,.08); }
    .f-select { width:100%; height:42px; border:1.5px solid #E2E8F0; border-radius:10px; padding:0 36px 0 12px; font-size:13px; color:#0F172A; outline:none; appearance:none; font-family:'Inter',sans-serif; background:#FFF url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%2394A3B8' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E") no-repeat right 12px center; cursor:pointer; transition:border-color .15s; }
    .f-select:focus { border-color:#22C55E; outline:none; box-shadow:0 0 0 3px rgba(34,197,94,.08); }
    .panel-section { background:#F8FAFC; border-radius:14px; padding:14px; margin-bottom:12px; }
    .panel-section-title { font-size:11px; font-weight:700; color:#0F172A; text-transform:uppercase; letter-spacing:.5px; margin-bottom:14px; display:flex; align-items:center; gap:7px; }
    .toast-container { position:fixed; bottom:28px; right:28px; z-index:9999; display:flex; flex-direction:column; gap:10px; }
    .toast { display:flex; align-items:center; gap:12px; padding:14px 18px; border-radius:14px; box-shadow:0 8px 24px rgba(15,23,42,.14); min-width:300px; max-width:400px; animation:slideIn .3s ease; background:#FFFFFF; }
    .toast.success { border-left:4px solid #16A34A; }
    .toast.error   { border-left:4px solid #EF4444; }
    @keyframes slideIn  { from { transform:translateX(120%); opacity:0; } to { transform:translateX(0); opacity:1; } }
    @keyframes slideOut { from { transform:translateX(0); opacity:1; } to { transform:translateX(120%); opacity:0; } }
    .modal-overlay { position:fixed; inset:0; background:rgba(15,23,42,.45); z-index:8888; display:flex; align-items:center; justify-content:center; backdrop-filter:blur(3px); }
    .modal-box { background:#FFFFFF; border-radius:20px; padding:32px; max-width:440px; width:90%; box-shadow:0 20px 60px rgba(15,23,42,.2); }
    .empty-state { display:flex; flex-direction:column; align-items:center; justify-content:center; padding:60px 20px; text-align:center; }
    .toggle-switch { position:relative; width:40px; height:22px; }
    .toggle-switch input { opacity:0; width:0; height:0; }
    .toggle-switch .slider { position:absolute; inset:0; background:#E2E8F0; border-radius:999px; cursor:pointer; transition:.2s; }
    .toggle-switch input:checked + .slider { background:#16A34A; }
    .toggle-switch .slider::before { content:''; position:absolute; width:16px; height:16px; left:3px; top:3px; background:#FFF; border-radius:50%; transition:.2s; }
    .toggle-switch input:checked + .slider::before { transform:translateX(18px); }
</style>
@endpush

@section('content')

<div class="toast-container" id="toast-container"></div>

<!-- Delete modal -->
<div class="modal-overlay" id="delete-modal" style="display:none;">
    <div class="modal-box">
        <div style="width:52px; height:52px; border-radius:14px; background:#FEF2F2; display:flex; align-items:center; justify-content:center; margin-bottom:20px;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#EF4444" stroke-width="2" stroke-linecap="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
        </div>
        <h3 style="font-size:18px; font-weight:700; color:#0F172A; margin:0 0 8px;">Supprimer cette filière ?</h3>
        <p style="font-size:14px; color:#64748B; line-height:1.6; margin:0 0 8px;">Filière : <strong id="delete-name" style="color:#0F172A;"></strong></p>
        <p style="font-size:13px; color:#EF4444; background:#FEF2F2; border-radius:8px; padding:10px 12px; margin:0 0 24px;">⚠ Impossible si des étudiants y sont inscrits. Action irréversible.</p>
        <div style="display:flex; gap:10px;">
            <button id="delete-cancel" style="flex:1;height:44px;border-radius:12px;border:1px solid #E2E8F0;background:#fff;font-size:14px;font-weight:600;color:#475569;cursor:pointer;font-family:'Inter',sans-serif;">Annuler</button>
            <button id="delete-confirm" style="flex:1.5;height:44px;border-radius:12px;border:none;background:#EF4444;font-size:14px;font-weight:600;color:#fff;cursor:pointer;font-family:'Inter',sans-serif;box-shadow:0 4px 12px rgba(239,68,68,.25);">Supprimer</button>
        </div>
    </div>
</div>

<div style="display:flex; height:100%; min-height:0; flex:1;">

    <!-- TABLE AREA -->
    <div style="flex:1; min-width:0; overflow-y:auto; display:flex; flex-direction:column;">

        <!-- Topbar -->
        <div style="padding:22px 24px 0; display:flex; justify-content:space-between; align-items:flex-start; gap:16px; flex-wrap:wrap; flex-shrink:0;">
            <div>
                <h1 style="font-size:24px; font-weight:700; color:#0F172A; margin:0; line-height:1.2;">Gestion des filières</h1>
                <p style="font-size:13px; color:#64748B; margin:4px 0 0;">Gérez les programmes et filières de votre université</p>
            </div>
            <div style="display:flex; align-items:center; gap:10px; flex-wrap:wrap;">
                <form method="GET" action="{{ route('filieres.index') }}" id="search-form" style="display:contents;">
                    <input type="hidden" name="statut" value="{{ request('statut','toutes') }}">
                    <div style="height:42px; background:#fff; border:1px solid #E2E8F0; border-radius:11px; display:flex; align-items:center; padding:0 13px; gap:8px; min-width:220px;">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#94A3B8" stroke-width="2" stroke-linecap="round" style="flex-shrink:0;"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        <input type="text" name="q" value="{{ request('q') }}" id="search-input" placeholder="Rechercher une filière…" style="border:none;outline:none;font-size:13px;color:#0F172A;background:transparent;flex:1;font-family:'Inter',sans-serif;min-width:0;">
                    </div>
                </form>
                <button id="btn-add" style="height:42px;padding:0 18px;background:#16A34A;border:none;border-radius:11px;display:flex;align-items:center;gap:7px;cursor:pointer;font-size:13px;font-weight:600;color:#FFF;box-shadow:0 4px 14px rgba(22,163,74,.22);font-family:'Inter',sans-serif;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Ajouter une filière
                </button>
            </div>
        </div>

        <!-- KPI -->
        <div style="padding:14px 24px 0; display:grid; grid-template-columns:repeat(4,1fr); gap:12px; flex-shrink:0;">
            @foreach([
                ['total',    'Total filières',  '#16A34A','#DCFCE7',$kpis['total']],
                ['actives',  'Actives',         '#3B82F6','#DBEAFE',$kpis['actives']],
                ['inactives','Inactives',       '#F97316','#FED7AA',$kpis['inactives']],
                ['etudiants','Total étudiants', '#8B5CF6','#DDD6FE',$kpis['etudiants']],
            ] as [$key,$l,$c,$bg,$v])
            <div class="kpi-mini">
                <div id="kpi-{{ $key }}" style="font-size:24px; font-weight:700; color:#0F172A; line-height:1;">{{ number_format($v) }}</div>
                <div style="font-size:12px; color:#64748B; margin-top:4px; display:flex; align-items:center; gap:5px;">
                    <div style="width:7px; height:7px; border-radius:50%; background:{{ $c }};"></div>{{ $l }}
                </div>
            </div>
            @endforeach
        </div>

        <!-- Tabs -->
        <div style="padding:12px 24px 0; flex-shrink:0;">
            <div style="display:inline-flex; gap:4px; background:#F8FAFC; padding:4px; border-radius:12px;">
                @foreach(['toutes' => 'Toutes', 'actif' => 'Actives', 'inactif' => 'Inactives'] as $val => $label)
                <a href="{{ route('filieres.index', array_merge(request()->query(), ['statut' => $val, 'page' => 1])) }}"
                   class="tab-btn {{ request('statut','toutes') === $val ? 'active' : '' }}" style="text-decoration:none;display:inline-flex;align-items:center;">
                    {{ $label }}
                </a>
                @endforeach
            </div>
        </div>

        <!-- Table -->
        <div style="margin:12px 24px 0; background:#FFFFFF; border-radius:18px; border:1px solid #F1F5F9; box-shadow:0 2px 10px rgba(15,23,42,.04); overflow:hidden; flex-shrink:0;">
            <div class="tbl-row tbl-header">
                @foreach(['Filière','Code','Niveau max','Étudiants','Statut','Actif','Actions'] as $col)
                <div style="font-size:10px; font-weight:700; letter-spacing:.6px; text-transform:uppercase; color:#94A3B8;">{{ $col }}</div>
                @endforeach
            </div>

            @forelse($filieres as $filiere)
            <div class="tbl-row" id="filiere-row-{{ $filiere->id }}">
                <div class="tbl-cell" style="display:flex; align-items:center; gap:12px;">
                    <div style="width:38px; height:38px; border-radius:10px; background:linear-gradient(135deg,#DCFCE7,#BBF7D0); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#16A34A" stroke-width="2" stroke-linecap="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
                    </div>
                    <div style="min-width:0;">
                        <div style="font-size:13px; font-weight:600; color:#0F172A; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $filiere->nom }}</div>
                        <div style="font-size:11px; color:#94A3B8; margin-top:1px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ Str::limit($filiere->description ?? '—', 40) }}</div>
                    </div>
                </div>
                <div class="tbl-cell" style="font-size:12px; font-weight:600; color:#475569; font-family:monospace;">{{ $filiere->code }}</div>
                <div class="tbl-cell"><span class="badge" style="background:#EFF6FF; color:#1D4ED8;">{{ $filiere->niveau_max }}</span></div>
                <div class="tbl-cell" style="font-size:14px; font-weight:700; color:#0F172A;">{{ number_format($filiere->students_count) }}</div>
                <div class="tbl-cell">
                    <span class="badge" style="background:{{ $filiere->actif ? '#DCFCE7' : '#FEE2E2' }}; color:{{ $filiere->actif ? '#15803D' : '#DC2626' }};">
                        {{ $filiere->actif ? 'Active' : 'Inactive' }}
                    </span>
                </div>
                <div class="tbl-cell">
                    <label class="toggle-switch">
                        <input type="checkbox" {{ $filiere->actif ? 'checked' : '' }} onchange="toggleActif({{ $filiere->id }}, this.checked)">
                        <span class="slider"></span>
                    </label>
                </div>
                <div class="tbl-cell" style="display:flex; gap:2px;">
                    <button class="act-btn btn-edit"
                        data-id="{{ $filiere->id }}" data-nom="{{ $filiere->nom }}" data-code="{{ $filiere->code }}"
                        data-description="{{ $filiere->description }}" data-niveau="{{ $filiere->niveau_max }}" data-actif="{{ $filiere->actif ? '1' : '0' }}"
                        title="Modifier">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#475569" stroke-width="2" stroke-linecap="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    </button>
                    <button class="act-btn btn-delete" data-id="{{ $filiere->id }}" data-name="{{ $filiere->nom }}" title="Supprimer">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#EF4444" stroke-width="2" stroke-linecap="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                    </button>
                </div>
            </div>
            @empty
            <div class="empty-state">
                <div style="width:60px; height:60px; border-radius:16px; background:#F1F5F9; display:flex; align-items:center; justify-content:center; margin-bottom:14px;">
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#94A3B8" stroke-width="1.5"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
                </div>
                <h3 style="font-size:16px; font-weight:700; color:#0F172A; margin:0 0 6px;">Aucune filière trouvée</h3>
                <p style="font-size:14px; color:#64748B; margin:0 0 20px;">{{ request('q') ? 'Aucun résultat pour "'.request('q').'".' : 'Commencez par créer votre première filière.' }}</p>
                <button id="btn-add-empty" style="height:40px;padding:0 20px;background:#16A34A;border:none;border-radius:10px;font-size:13px;font-weight:600;color:#fff;cursor:pointer;font-family:'Inter',sans-serif;">+ Ajouter une filière</button>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div style="padding:14px 24px 24px; display:flex; justify-content:space-between; align-items:center; flex-shrink:0;">
            <div style="font-size:13px; color:#64748B;">
                <strong>{{ $filieres->total() }}</strong> filière(s) au total
            </div>
            <div>{{ $filieres->links('pagination::simple-tailwind') }}</div>
        </div>
    </div>

    <!-- SIDE PANEL -->
    <div id="add-panel" style="width:340px; flex-shrink:0; border-left:1px solid #EAEFF5; background:#FFFFFF; overflow-y:auto; display:none; flex-direction:column;">
        <div style="padding:18px 20px 14px; display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid #F1F5F9; flex-shrink:0; position:sticky; top:0; background:#fff; z-index:10;">
            <div>
                <h2 id="panel-title" style="font-size:15px; font-weight:700; color:#0F172A; margin:0;">Ajouter une filière</h2>
                <p style="font-size:11px; color:#94A3B8; margin:3px 0 0;">Remplissez les informations ci-dessous</p>
            </div>
            <button id="btn-close" style="width:28px;height:28px;border-radius:7px;border:1px solid #E2E8F0;background:#fff;display:flex;align-items:center;justify-content:center;cursor:pointer;">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#475569" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>

        <form id="filiere-form" style="flex:1; overflow-y:auto; padding:16px 20px;">
            @csrf
            <input type="hidden" id="form-method" name="_method" value="POST">
            <input type="hidden" id="form-id" value="">

            <div class="panel-section">
                <div class="panel-section-title">
                    <div style="width:22px;height:22px;border-radius:6px;background:#DCFCE7;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#16A34A" stroke-width="2"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
                    </div>
                    Informations
                </div>
                <div style="display:flex; flex-direction:column; gap:10px;">
                    <div><label class="f-label">Nom de la filière *</label><input type="text" name="nom" id="f-nom" class="f-input" placeholder="Ex : Informatique" required></div>
                    <div><label class="f-label">Code *</label><input type="text" name="code" id="f-code" class="f-input" placeholder="Ex : INFO" style="text-transform:uppercase;" maxlength="20" required></div>
                    <div>
                        <label class="f-label">Niveau maximum *</label>
                        <select name="niveau_max" id="f-niveau" class="f-select">
                            @foreach(['L1','L2','L3','M1','M2'] as $n)
                            <option value="{{ $n }}">{{ $n }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div><label class="f-label">Description</label><textarea name="description" id="f-description" class="f-textarea" placeholder="Description de la filière…"></textarea></div>
                    <div style="display:flex; align-items:center; justify-content:space-between; background:#F8FAFC; border-radius:10px; padding:12px 14px;">
                        <div>
                            <div style="font-size:13px; font-weight:600; color:#0F172A;">Filière active</div>
                            <div style="font-size:11px; color:#64748B; margin-top:2px;">Visible pour les inscriptions</div>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" name="actif" id="f-actif" checked>
                            <span class="slider"></span>
                        </label>
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
(function () {
    const panel    = document.getElementById('add-panel');
    const btnAdd   = document.getElementById('btn-add');
    const btnAE    = document.getElementById('btn-add-empty');
    const btnClose = document.getElementById('btn-close');
    const btnCancel= document.getElementById('btn-cancel');
    const btnSave  = document.getElementById('btn-save');
    const form     = document.getElementById('filiere-form');
    const csrf     = '{{ csrf_token() }}';

    function showPanel(mode='add') {
        panel.style.display = 'flex'; panel.style.flexDirection = 'column';
        document.getElementById('panel-title').textContent = mode === 'add' ? 'Ajouter une filière' : 'Modifier la filière';
        if (mode === 'add') {
            document.getElementById('form-method').value = 'POST';
            document.getElementById('form-id').value = '';
            form.querySelectorAll('input[type=text],textarea').forEach(el => el.value = '');
            document.getElementById('f-niveau').value = 'M2';
            document.getElementById('f-actif').checked = true;
        }
    }
    function hidePanel() { panel.style.display = 'none'; }

    if(btnAdd)  btnAdd.addEventListener('click',  () => showPanel('add'));
    if(btnAE)   btnAE.addEventListener('click',   () => showPanel('add'));
    if(btnClose) btnClose.addEventListener('click', hidePanel);
    if(btnCancel) btnCancel.addEventListener('click', hidePanel);

    document.querySelectorAll('.btn-edit').forEach(btn => {
        btn.addEventListener('click', () => {
            showPanel('edit');
            const d = btn.dataset;
            document.getElementById('form-method').value = 'PUT';
            document.getElementById('form-id').value = d.id;
            document.getElementById('f-nom').value = d.nom || '';
            document.getElementById('f-code').value = d.code || '';
            document.getElementById('f-description').value = d.description || '';
            document.getElementById('f-niveau').value = d.niveau || 'M2';
            document.getElementById('f-actif').checked = d.actif === '1';
        });
    });

    btnSave.addEventListener('click', async () => {
        const id     = document.getElementById('form-id').value;
        const method = document.getElementById('form-method').value;
        const url    = id ? `/filieres/${id}` : '/filieres';
        const data   = new FormData(form);
        data.set('_method', method);
        if (!document.getElementById('f-actif').checked) data.set('actif', '0');

        btnSave.disabled = true;
        document.getElementById('save-spinner').style.display = 'inline-block';
        document.getElementById('save-text').textContent = 'Enregistrement…';

        try {
            const res  = await fetch(url, { method:'POST', headers:{'X-CSRF-TOKEN':csrf,'Accept':'application/json'}, body:data });
            const json = await res.json();
            if (json.success) {
                showToast(json.message,'success');
                hidePanel();
                // Mise à jour KPIs temps réel
                if (json.kpis) {
                    const m={total:'kpi-total',actives:'kpi-actives',inactives:'kpi-inactives',etudiants:'kpi-etudiants'};
                    Object.entries(m).forEach(([k,id])=>{const el=document.getElementById(id);if(el&&json.kpis[k]!==undefined)el.textContent=Number(json.kpis[k]).toLocaleString('fr-FR');});
                }
                // Recharger uniquement si nouvelle filière (pas d'update en place pour simplifier)
                setTimeout(()=>location.reload(),1200);
            }
            else showToast(json.message||'Erreur.','error');
        } catch { showToast('Erreur réseau.','error'); }
        finally {
            btnSave.disabled = false;
            document.getElementById('save-spinner').style.display = 'none';
            document.getElementById('save-text').textContent = 'Enregistrer';
        }
    });

    // Toggle actif inline
    window.toggleActif = async (id, actif) => {
        const data = new FormData();
        // On récupère les données du bouton edit
        const btn = document.querySelector(`.btn-edit[data-id="${id}"]`);
        if (!btn) return;
        data.set('_method','PUT');
        data.set('nom', btn.dataset.nom);
        data.set('code', btn.dataset.code);
        data.set('description', btn.dataset.description || '');
        data.set('niveau_max', btn.dataset.niveau);
        data.set('actif', actif ? '1' : '0');
        try {
            const res  = await fetch(`/filieres/${id}`, { method:'POST', headers:{'X-CSRF-TOKEN':csrf,'Accept':'application/json'}, body:data });
            const json = await res.json();
            if (json.success) showToast(json.message,'success');
            else showToast(json.message||'Erreur.','error');
        } catch { showToast('Erreur réseau.','error'); }
    };

    // Delete
    const deleteModal  = document.getElementById('delete-modal');
    const deleteConfirm= document.getElementById('delete-confirm');
    const deleteName   = document.getElementById('delete-name');
    let pendingId = null;

    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', () => { pendingId = btn.dataset.id; deleteName.textContent = btn.dataset.name; deleteModal.style.display='flex'; });
    });
    document.getElementById('delete-cancel').addEventListener('click', ()=>{ deleteModal.style.display='none'; pendingId=null; });
    deleteModal.addEventListener('click', e => { if(e.target===deleteModal){ deleteModal.style.display='none'; pendingId=null; } });

    deleteConfirm.addEventListener('click', async () => {
        if (!pendingId) return;
        deleteConfirm.disabled=true; deleteConfirm.textContent='Suppression…';
        try {
            const res  = await fetch(`/filieres/${pendingId}`, { method:'POST', headers:{'X-CSRF-TOKEN':csrf,'Accept':'application/json','Content-Type':'application/x-www-form-urlencoded'}, body:'_method=DELETE' });
            const json = await res.json();
            if (json.success) {
                deleteModal.style.display='none';
                const row=document.getElementById(`filiere-row-${pendingId}`);
                if(row){row.style.transition='opacity .3s ease';row.style.opacity='0';setTimeout(()=>row.remove(),300);}
                showToast(json.message,'success');
                if(json.kpis){const m={total:'kpi-total',actives:'kpi-actives',inactives:'kpi-inactives',etudiants:'kpi-etudiants'};Object.entries(m).forEach(([k,id])=>{const el=document.getElementById(id);if(el&&json.kpis[k]!==undefined)el.textContent=Number(json.kpis[k]).toLocaleString('fr-FR');});}
            } else showToast(json.message||'Erreur.','error');
        } catch { showToast('Erreur réseau.','error'); }
        finally { deleteConfirm.disabled=false; deleteConfirm.textContent='Supprimer'; pendingId=null; }
    });

    // Search debounce
    const si = document.getElementById('search-input');
    const sf = document.getElementById('search-form');
    let t;
    si?.addEventListener('input', ()=>{ clearTimeout(t); t=setTimeout(()=>sf.submit(),500); });

    // Toast
    function showToast(message, type='success') {
        const c = document.getElementById('toast-container');
        const el = document.createElement('div');
        el.className = `toast ${type}`;
        el.innerHTML = `<div style="width:30px;height:30px;border-radius:8px;background:${type==='success'?'#DCFCE7':'#FEE2E2'};display:flex;align-items:center;justify-content:center;flex-shrink:0;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="${type==='success'?'#16A34A':'#EF4444'}" stroke-width="2.5" stroke-linecap="round">${type==='success'?'<polyline points="20 6 9 17 4 12"/>':'<line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>'}</svg></div><div style="flex:1;min-width:0;"><div style="font-size:13px;font-weight:600;color:#0F172A;">${type==='success'?'Succès':'Erreur'}</div><div style="font-size:12px;color:#64748B;margin-top:2px;line-height:1.4;">${message}</div></div><button onclick="this.parentElement.remove()" style="background:none;border:none;cursor:pointer;color:#94A3B8;font-size:16px;flex-shrink:0;padding:0;">×</button>`;
        c.appendChild(el);
        setTimeout(()=>{ el.style.animation='slideOut .3s ease forwards'; setTimeout(()=>el.remove(),300); },4000);
    }
})();
</script>
@endpush
