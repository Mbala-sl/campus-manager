@extends('layouts.admin')
@section('title', 'Années académiques — Campus Manager')
@section('nav_annees', 'active')

@push('styles')
<style>
    .kpi-mini { background:#FFFFFF; border-radius:16px; padding:16px 20px; border:1px solid #F1F5F9; box-shadow:0 2px 8px rgba(15,23,42,.04); }
    .annee-card { background:#FFFFFF; border-radius:18px; border:1px solid #F1F5F9; box-shadow:0 2px 10px rgba(15,23,42,.05); padding:20px 24px; transition:transform .2s,box-shadow .2s; }
    .annee-card:hover { transform:translateY(-2px); box-shadow:0 8px 24px rgba(15,23,42,.09); }
    .badge { display:inline-flex; align-items:center; height:24px; padding:0 9px; border-radius:999px; font-size:11px; font-weight:600; white-space:nowrap; }
    .act-btn { width:30px; height:30px; border-radius:8px; border:none; background:transparent; display:inline-flex; align-items:center; justify-content:center; cursor:pointer; transition:background .12s; }
    .act-btn:hover { background:#F1F5F9; }
    .f-label { font-size:11px; font-weight:600; color:#94A3B8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:6px; display:block; }
    .f-input { width:100%; height:42px; border:1.5px solid #E2E8F0; border-radius:10px; padding:0 12px; font-size:13px; color:#0F172A; outline:none; font-family:'Inter',sans-serif; transition:border-color .15s,box-shadow .15s; background:#FFF; }
    .f-input:focus { border-color:#22C55E; box-shadow:0 0 0 3px rgba(34,197,94,.08); }
    .f-textarea { width:100%; border:1.5px solid #E2E8F0; border-radius:10px; padding:10px 12px; font-size:13px; color:#0F172A; outline:none; font-family:'Inter',sans-serif; resize:vertical; min-height:70px; transition:border-color .15s; background:#FFF; }
    .f-textarea:focus { border-color:#22C55E; box-shadow:0 0 0 3px rgba(34,197,94,.08); }
    .panel-section { background:#F8FAFC; border-radius:14px; padding:14px; margin-bottom:12px; }
    .panel-section-title { font-size:11px; font-weight:700; color:#0F172A; text-transform:uppercase; letter-spacing:.5px; margin-bottom:14px; display:flex; align-items:center; gap:7px; }
    .toggle-switch { position:relative; width:40px; height:22px; }
    .toggle-switch input { opacity:0; width:0; height:0; }
    .toggle-switch .slider { position:absolute; inset:0; background:#E2E8F0; border-radius:999px; cursor:pointer; transition:.2s; }
    .toggle-switch input:checked + .slider { background:#16A34A; }
    .toggle-switch .slider::before { content:''; position:absolute; width:16px; height:16px; left:3px; top:3px; background:#FFF; border-radius:50%; transition:.2s; }
    .toggle-switch input:checked + .slider::before { transform:translateX(18px); }
    .toast-container { position:fixed; bottom:28px; right:28px; z-index:9999; display:flex; flex-direction:column; gap:10px; }
    .toast { display:flex; align-items:center; gap:12px; padding:14px 18px; border-radius:14px; box-shadow:0 8px 24px rgba(15,23,42,.14); min-width:300px; max-width:400px; animation:slideIn .3s ease; background:#FFFFFF; }
    .toast.success { border-left:4px solid #16A34A; } .toast.error { border-left:4px solid #EF4444; }
    @keyframes slideIn  { from { transform:translateX(120%); opacity:0; } to { transform:translateX(0); opacity:1; } }
    @keyframes slideOut { from { transform:translateX(0); opacity:1; } to { transform:translateX(120%); opacity:0; } }
    .modal-overlay { position:fixed; inset:0; background:rgba(15,23,42,.45); z-index:8888; display:flex; align-items:center; justify-content:center; backdrop-filter:blur(3px); }
    .modal-box { background:#FFFFFF; border-radius:20px; padding:32px; max-width:440px; width:90%; box-shadow:0 20px 60px rgba(15,23,42,.2); }
</style>
@endpush

@section('content')

<div class="toast-container" id="toast-container"></div>

<div class="modal-overlay" id="delete-modal" style="display:none;">
    <div class="modal-box">
        <div style="width:52px;height:52px;border-radius:14px;background:#FEF2F2;display:flex;align-items:center;justify-content:center;margin-bottom:20px;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#EF4444" stroke-width="2" stroke-linecap="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/></svg>
        </div>
        <h3 style="font-size:18px;font-weight:700;color:#0F172A;margin:0 0 8px;">Supprimer cette année ?</h3>
        <p style="font-size:14px;color:#64748B;margin:0 0 8px;">Année : <strong id="delete-name" style="color:#0F172A;"></strong></p>
        <p style="font-size:13px;color:#EF4444;background:#FEF2F2;border-radius:8px;padding:10px 12px;margin:0 0 24px;">⚠ Impossible de supprimer l'année en cours. Action irréversible.</p>
        <div style="display:flex;gap:10px;">
            <button id="delete-cancel" style="flex:1;height:44px;border-radius:12px;border:1px solid #E2E8F0;background:#fff;font-size:14px;font-weight:600;color:#475569;cursor:pointer;font-family:'Inter',sans-serif;">Annuler</button>
            <button id="delete-confirm" style="flex:1.5;height:44px;border-radius:12px;border:none;background:#EF4444;font-size:14px;font-weight:600;color:#fff;cursor:pointer;font-family:'Inter',sans-serif;">Supprimer</button>
        </div>
    </div>
</div>

<div style="display:flex; height:100%; min-height:0; flex:1;">

    <div style="flex:1; min-width:0; overflow-y:auto; display:flex; flex-direction:column;">

        <!-- Topbar -->
        <div style="padding:22px 24px 0; display:flex; justify-content:space-between; align-items:flex-start; gap:16px; flex-shrink:0;">
            <div>
                <h1 style="font-size:24px; font-weight:700; color:#0F172A; margin:0; line-height:1.2;">Années académiques</h1>
                <p style="font-size:13px; color:#64748B; margin:4px 0 0;">Gérez les périodes académiques de votre université</p>
            </div>
            <button id="btn-add" style="height:42px;padding:0 18px;background:#16A34A;border:none;border-radius:11px;display:flex;align-items:center;gap:7px;cursor:pointer;font-size:13px;font-weight:600;color:#FFF;box-shadow:0 4px 14px rgba(22,163,74,.22);font-family:'Inter',sans-serif;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Nouvelle année
            </button>
        </div>

        <!-- KPI -->
        <div style="padding:14px 24px 0; display:grid; grid-template-columns:repeat(4,1fr); gap:12px; flex-shrink:0;">
            @foreach([
                ['Années totales',      $kpis['total'],     '#16A34A','#DCFCE7'],
                ['Année en cours',      $kpis['actuelle'],  '#3B82F6','#DBEAFE'],
                ['Inscriptions ouvertes',$kpis['ouvertes'], '#F97316','#FED7AA'],
                ['Étudiants actifs',    $kpis['etudiants'], '#8B5CF6','#DDD6FE'],
            ] as [$l,$v,$c,$bg])
            <div class="kpi-mini">
                <div style="font-size:24px; font-weight:700; color:#0F172A; line-height:1;">{{ number_format($v) }}</div>
                <div style="font-size:12px; color:#64748B; margin-top:4px; display:flex; align-items:center; gap:5px;">
                    <div style="width:7px; height:7px; border-radius:50%; background:{{ $c }};"></div>{{ $l }}
                </div>
            </div>
            @endforeach
        </div>

        <!-- Cards grid -->
        <div style="padding:14px 24px; display:grid; grid-template-columns:repeat(auto-fill,minmax(340px,1fr)); gap:16px; flex-shrink:0;">
            @forelse($annees as $annee)
            @php [$sBg,$sFg] = $annee->statut_colors; @endphp
            <div class="annee-card" id="annee-card-{{ $annee->id }}">
                <!-- Header -->
                <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:16px;">
                    <div style="display:flex; align-items:center; gap:12px;">
                        <div style="width:44px; height:44px; border-radius:12px; background:{{ $annee->actif ? 'linear-gradient(135deg,#22C55E,#16A34A)' : 'linear-gradient(135deg,#F1F5F9,#E2E8F0)' }}; display:flex; align-items:center; justify-content:center;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="{{ $annee->actif ? 'white' : '#94A3B8' }}" stroke-width="2" stroke-linecap="round">
                                <rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                            </svg>
                        </div>
                        <div>
                            <div style="font-size:18px; font-weight:800; color:#0F172A; letter-spacing:-.3px;">{{ $annee->label }}</div>
                            <div style="font-size:11px; color:#94A3B8; margin-top:1px;">{{ $annee->date_debut?->format('d/m/Y') }} — {{ $annee->date_fin?->format('d/m/Y') }}</div>
                        </div>
                    </div>
                    <div style="display:flex; gap:4px; align-items:center;">
                        <span class="badge" style="background:{{ $sBg }}; color:{{ $sFg }};">{{ $annee->statut_label }}</span>
                    </div>
                </div>

                <!-- Stats -->
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:16px;">
                    <div style="background:#F8FAFC; border-radius:10px; padding:12px 14px;">
                        <div style="font-size:20px; font-weight:700; color:#0F172A;">{{ number_format($annee->nb_etudiants) }}</div>
                        <div style="font-size:11px; color:#64748B; margin-top:2px;">Étudiants</div>
                    </div>
                    <div style="background:#F8FAFC; border-radius:10px; padding:12px 14px;">
                        <div style="font-size:20px; font-weight:700; color:#0F172A;">{{ number_format($annee->nb_inscriptions) }}</div>
                        <div style="font-size:11px; color:#64748B; margin-top:2px;">Inscriptions</div>
                    </div>
                </div>

                <!-- Toggles + actions -->
                <div style="display:flex; justify-content:space-between; align-items:center; padding-top:14px; border-top:1px solid #F1F5F9;">
                    <div style="display:flex; gap:16px; align-items:center;">
                        <div style="display:flex; align-items:center; gap:7px;">
                            <label class="toggle-switch">
                                <input type="checkbox" {{ $annee->actif ? 'checked' : '' }} onchange="setActif({{ $annee->id }}, this.checked)" {{ $annee->actif ? 'disabled' : '' }}>
                                <span class="slider"></span>
                            </label>
                            <span style="font-size:12px; color:#475569;">En cours</span>
                        </div>
                        <div style="display:flex; align-items:center; gap:7px;">
                            <label class="toggle-switch">
                                <input type="checkbox" {{ $annee->ouvert_inscriptions ? 'checked' : '' }} onchange="setInscriptions({{ $annee->id }}, this.checked)">
                                <span class="slider"></span>
                            </label>
                            <span style="font-size:12px; color:#475569;">Inscriptions ouvertes</span>
                        </div>
                    </div>
                    <div style="display:flex; gap:4px;">
                        <button class="act-btn btn-edit"
                            data-id="{{ $annee->id }}" data-label="{{ $annee->label }}"
                            data-debut="{{ $annee->date_debut?->format('Y-m-d') }}" data-fin="{{ $annee->date_fin?->format('Y-m-d') }}"
                            data-actif="{{ $annee->actif ? '1' : '0' }}" data-ouvert="{{ $annee->ouvert_inscriptions ? '1' : '0' }}"
                            data-description="{{ $annee->description }}"
                            title="Modifier">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#475569" stroke-width="2" stroke-linecap="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        </button>
                        <button class="act-btn btn-delete" data-id="{{ $annee->id }}" data-name="{{ $annee->label }}" title="Supprimer">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#EF4444" stroke-width="2" stroke-linecap="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/></svg>
                        </button>
                    </div>
                </div>
            </div>
            @empty
            <div style="grid-column:1/-1; display:flex; flex-direction:column; align-items:center; justify-content:center; padding:60px 20px; text-align:center;">
                <div style="width:60px;height:60px;border-radius:16px;background:#F1F5F9;display:flex;align-items:center;justify-content:center;margin-bottom:14px;">
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#94A3B8" stroke-width="1.5"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                </div>
                <h3 style="font-size:16px;font-weight:700;color:#0F172A;margin:0 0 6px;">Aucune année académique</h3>
                <p style="font-size:14px;color:#64748B;margin:0 0 20px;">Créez votre première année académique.</p>
                <button id="btn-add-empty" style="height:40px;padding:0 20px;background:#16A34A;border:none;border-radius:10px;font-size:13px;font-weight:600;color:#fff;cursor:pointer;font-family:'Inter',sans-serif;">+ Nouvelle année</button>
            </div>
            @endforelse
        </div>

        <div style="padding:0 24px 24px; flex-shrink:0;">{{ $annees->links('pagination::simple-tailwind') }}</div>
    </div>

    <!-- SIDE PANEL -->
    <div id="add-panel" style="width:340px; flex-shrink:0; border-left:1px solid #EAEFF5; background:#FFFFFF; overflow-y:auto; display:none; flex-direction:column;">
        <div style="padding:18px 20px 14px; display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid #F1F5F9; flex-shrink:0; position:sticky; top:0; background:#fff; z-index:10;">
            <div>
                <h2 id="panel-title" style="font-size:15px; font-weight:700; color:#0F172A; margin:0;">Nouvelle année</h2>
                <p style="font-size:11px; color:#94A3B8; margin:3px 0 0;">Définissez la période académique</p>
            </div>
            <button id="btn-close" style="width:28px;height:28px;border-radius:7px;border:1px solid #E2E8F0;background:#fff;display:flex;align-items:center;justify-content:center;cursor:pointer;">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#475569" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>

        <form id="annee-form" style="flex:1; overflow-y:auto; padding:16px 20px;">
            @csrf
            <input type="hidden" id="form-method" name="_method" value="POST">
            <input type="hidden" id="form-id" value="">

            <div class="panel-section">
                <div class="panel-section-title">
                    <div style="width:22px;height:22px;border-radius:6px;background:#DCFCE7;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#16A34A" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    </div>
                    Période
                </div>
                <div style="display:flex; flex-direction:column; gap:10px;">
                    <div>
                        <label class="f-label">Label * (ex: 2025-2026)</label>
                        <input type="text" name="label" id="f-label" class="f-input" placeholder="2025-2026" pattern="\d{4}-\d{4}" required>
                    </div>
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px;">
                        <div><label class="f-label">Début *</label><input type="date" name="date_debut" id="f-debut" class="f-input" style="font-size:12px;" min="2019-01-01" max="2040-12-31" required></div>
                        <div><label class="f-label">Fin *</label><input type="date" name="date_fin" id="f-fin" class="f-input" style="font-size:12px;" min="2019-06-01" max="2041-06-30" required></div>
                    </div>
                    <div><label class="f-label">Description</label><textarea name="description" id="f-description" class="f-textarea" placeholder="Notes sur cette année académique…"></textarea></div>

                    <div style="display:flex; flex-direction:column; gap:8px; background:#F8FAFC; border-radius:10px; padding:12px 14px;">
                        <div style="display:flex; justify-content:space-between; align-items:center;">
                            <div>
                                <div style="font-size:13px; font-weight:600; color:#0F172A;">Année en cours</div>
                                <div style="font-size:11px; color:#64748B; margin-top:1px;">Désactive l'année précédente</div>
                            </div>
                            <label class="toggle-switch"><input type="checkbox" name="actif" id="f-actif"><span class="slider"></span></label>
                        </div>
                        <div style="border-top:1px solid #E2E8F0; padding-top:8px; display:flex; justify-content:space-between; align-items:center;">
                            <div>
                                <div style="font-size:13px; font-weight:600; color:#0F172A;">Inscriptions ouvertes</div>
                                <div style="font-size:11px; color:#64748B; margin-top:1px;">Permet les nouvelles inscriptions</div>
                            </div>
                            <label class="toggle-switch"><input type="checkbox" name="ouvert_inscriptions" id="f-ouvert"><span class="slider"></span></label>
                        </div>
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
    const panel=document.getElementById('add-panel'), csrf='{{ csrf_token() }}';
    const btnSave=document.getElementById('btn-save'), form=document.getElementById('annee-form');

    function showPanel(mode='add') {
        panel.style.display='flex'; panel.style.flexDirection='column';
        document.getElementById('panel-title').textContent = mode==='add' ? 'Nouvelle année académique' : 'Modifier l\'année';
        if (mode==='add') {
            document.getElementById('form-method').value='POST'; document.getElementById('form-id').value='';
            document.getElementById('f-label').value=''; document.getElementById('f-debut').value='';
            document.getElementById('f-fin').value=''; document.getElementById('f-description').value='';
            document.getElementById('f-actif').checked=false; document.getElementById('f-ouvert').checked=false;
        }
    }
    function hidePanel() { panel.style.display='none'; }

    document.getElementById('btn-add')?.addEventListener('click',()=>showPanel('add'));
    document.getElementById('btn-add-empty')?.addEventListener('click',()=>showPanel('add'));
    document.getElementById('btn-close')?.addEventListener('click',hidePanel);
    document.getElementById('btn-cancel')?.addEventListener('click',hidePanel);

    document.querySelectorAll('.btn-edit').forEach(btn=>{
        btn.addEventListener('click',()=>{
            showPanel('edit');
            const d=btn.dataset;
            document.getElementById('form-method').value='PUT'; document.getElementById('form-id').value=d.id;
            document.getElementById('f-label').value=d.label||''; document.getElementById('f-debut').value=d.debut||'';
            document.getElementById('f-fin').value=d.fin||''; document.getElementById('f-description').value=d.description||'';
            document.getElementById('f-actif').checked=d.actif==='1'; document.getElementById('f-ouvert').checked=d.ouvert==='1';
        });
    });

    btnSave.addEventListener('click', async()=>{
        const id=document.getElementById('form-id').value, method=document.getElementById('form-method').value;
        const url=id?`/annees/${id}`:'/annees';
        const data=new FormData(form); data.set('_method',method);
        if(!document.getElementById('f-actif').checked) data.set('actif','0');
        if(!document.getElementById('f-ouvert').checked) data.set('ouvert_inscriptions','0');
        btnSave.disabled=true; document.getElementById('save-spinner').style.display='inline-block'; document.getElementById('save-text').textContent='Enregistrement…';
        try {
            const res=await fetch(url,{method:'POST',headers:{'X-CSRF-TOKEN':csrf,'Accept':'application/json'},body:data});
            const json=await res.json();
            if(json.success){showToast(json.message,'success');hidePanel();setTimeout(()=>location.reload(),1200);}
            else showToast(json.message||'Erreur.','error');
        } catch{showToast('Erreur réseau.','error');}
        finally{btnSave.disabled=false;document.getElementById('save-spinner').style.display='none';document.getElementById('save-text').textContent='Enregistrer';}
    });

    // Toggle actif inline
    window.setActif = async(id,val)=>{
        const btn=document.querySelector(`.btn-edit[data-id="${id}"]`);
        if(!btn)return;
        const d=btn.dataset, data=new FormData();
        data.set('_method','PUT'); data.set('label',d.label); data.set('date_debut',d.debut); data.set('date_fin',d.fin);
        data.set('actif',val?'1':'0'); data.set('ouvert_inscriptions',d.ouvert);
        if(d.description) data.set('description',d.description);
        try{const res=await fetch(`/annees/${id}`,{method:'POST',headers:{'X-CSRF-TOKEN':csrf,'Accept':'application/json'},body:data});const json=await res.json();if(json.success){showToast(json.message,'success');setTimeout(()=>location.reload(),1200);}else showToast(json.message||'Erreur.','error');}catch{showToast('Erreur réseau.','error');}
    };

    window.setInscriptions = async(id,val)=>{
        const btn=document.querySelector(`.btn-edit[data-id="${id}"]`);
        if(!btn)return;
        const d=btn.dataset, data=new FormData();
        data.set('_method','PUT'); data.set('label',d.label); data.set('date_debut',d.debut); data.set('date_fin',d.fin);
        data.set('actif',d.actif); data.set('ouvert_inscriptions',val?'1':'0');
        if(d.description) data.set('description',d.description);
        try{const res=await fetch(`/annees/${id}`,{method:'POST',headers:{'X-CSRF-TOKEN':csrf,'Accept':'application/json'},body:data});const json=await res.json();if(json.success)showToast(json.message,'success');else showToast(json.message||'Erreur.','error');}catch{showToast('Erreur réseau.','error');}
    };

    // Delete
    const deleteModal=document.getElementById('delete-modal'), deleteConfirm=document.getElementById('delete-confirm');
    let pendingId=null;
    document.querySelectorAll('.btn-delete').forEach(btn=>{btn.addEventListener('click',()=>{pendingId=btn.dataset.id;document.getElementById('delete-name').textContent=btn.dataset.name;deleteModal.style.display='flex';});});
    document.getElementById('delete-cancel').addEventListener('click',()=>{deleteModal.style.display='none';pendingId=null;});
    deleteModal.addEventListener('click',e=>{if(e.target===deleteModal){deleteModal.style.display='none';pendingId=null;}});
    deleteConfirm.addEventListener('click',async()=>{
        if(!pendingId)return; deleteConfirm.disabled=true; deleteConfirm.textContent='Suppression…';
        try{const res=await fetch(`/annees/${pendingId}`,{method:'POST',headers:{'X-CSRF-TOKEN':csrf,'Accept':'application/json','Content-Type':'application/x-www-form-urlencoded'},body:'_method=DELETE'});const json=await res.json();
        if(json.success){
            deleteModal.style.display='none';
            const card=document.getElementById(`annee-card-${pendingId}`);
            if(card){card.style.transition='opacity .3s ease';card.style.opacity='0';setTimeout(()=>card.remove(),300);}
            showToast(json.message,'success');
        }else showToast(json.message||'Erreur.','error');}catch{showToast('Erreur réseau.','error');}
        finally{deleteConfirm.disabled=false;deleteConfirm.textContent='Supprimer';pendingId=null;}
    });

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
