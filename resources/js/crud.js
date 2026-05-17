/**
 * Campus Manager — Module CRUD temps réel
 * Fonctions partagées : toast, modal, panel, KPI update
 */

const AVATAR_COLORS = ['#BBF7D0','#BAE6FD','#FDE68A','#FBCFE8','#DDD6FE','#FED7AA'];
const csrf = () => document.querySelector('meta[name="csrf-token"]')?.content ?? '';

// ── Toast ──────────────────────────────────────────────────────────
function showToast(message, type = 'success') {
    let container = document.getElementById('toast-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'toast-container';
        container.style.cssText = 'position:fixed;bottom:28px;right:28px;z-index:9999;display:flex;flex-direction:column;gap:10px;';
        document.body.appendChild(container);
    }

    const toast = document.createElement('div');
    const isSuccess = type === 'success';
    toast.style.cssText = `display:flex;align-items:center;gap:12px;padding:14px 18px;border-radius:14px;box-shadow:0 8px 24px rgba(15,23,42,.14);min-width:300px;max-width:420px;background:#FFFFFF;border-left:4px solid ${isSuccess ? '#16A34A' : '#EF4444'};animation:cmSlideIn .3s ease;`;
    toast.innerHTML = `
        <div style="width:30px;height:30px;border-radius:8px;background:${isSuccess ? '#DCFCE7' : '#FEE2E2'};display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="${isSuccess ? '#16A34A' : '#EF4444'}" stroke-width="2.5" stroke-linecap="round">
                ${isSuccess ? '<polyline points="20 6 9 17 4 12"/>' : '<line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>'}
            </svg>
        </div>
        <div style="flex:1;min-width:0;">
            <div style="font-size:13px;font-weight:600;color:#0F172A;">${isSuccess ? 'Succès' : 'Erreur'}</div>
            <div style="font-size:12px;color:#64748B;margin-top:2px;line-height:1.4;">${message}</div>
        </div>
        <button onclick="this.parentElement.remove()" style="background:none;border:none;cursor:pointer;color:#94A3B8;font-size:18px;flex-shrink:0;padding:0;line-height:1;">×</button>
    `;
    container.appendChild(toast);
    setTimeout(() => {
        toast.style.animation = 'cmSlideOut .3s ease forwards';
        setTimeout(() => toast.remove(), 300);
    }, 4500);
}

// ── Inject animations once ──────────────────────────────────────────
if (!document.getElementById('cm-anim-style')) {
    const s = document.createElement('style');
    s.id = 'cm-anim-style';
    s.textContent = `
        @keyframes cmSlideIn  { from{transform:translateX(120%);opacity:0} to{transform:translateX(0);opacity:1} }
        @keyframes cmSlideOut { from{transform:translateX(0);opacity:1}   to{transform:translateX(120%);opacity:0} }
        @keyframes cmFadeIn   { from{opacity:0;transform:translateY(-6px)} to{opacity:1;transform:translateY(0)} }
        @keyframes cmSpin     { to{transform:rotate(360deg)} }
    `;
    document.head.appendChild(s);
}

// ── Delete modal ────────────────────────────────────────────────────
function setupDeleteModal({ modalId, cancelId, confirmId, nameId, getUrl, onSuccess }) {
    const modal   = document.getElementById(modalId);
    const confirm = document.getElementById(confirmId);
    const cancel  = document.getElementById(cancelId);
    const nameEl  = document.getElementById(nameId);
    let pending   = null;

    function open(id, name) { pending = id; nameEl.textContent = name; modal.style.display = 'flex'; }
    function close()        { pending = null; modal.style.display = 'none'; }

    cancel?.addEventListener('click', close);
    modal?.addEventListener('click', e => { if (e.target === modal) close(); });

    confirm?.addEventListener('click', async () => {
        if (!pending) return;
        confirm.disabled = true;
        confirm.textContent = 'Suppression…';

        try {
            const res  = await fetch(getUrl(pending), {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrf(), 'Accept': 'application/json', 'Content-Type': 'application/x-www-form-urlencoded' },
                body: '_method=DELETE',
            });
            const json = await res.json();
            if (json.success) {
                close();
                showToast(json.message, 'success');
                onSuccess(pending, json);
            } else {
                showToast(json.message || 'Erreur.', 'error');
            }
        } catch { showToast('Erreur réseau.', 'error'); }
        finally { confirm.disabled = false; confirm.textContent = 'Supprimer définitivement'; }
    });

    return { open };
}

// ── Panel ───────────────────────────────────────────────────────────
function setupPanel(panelId, { onShow, onHide } = {}) {
    const panel = document.getElementById(panelId);

    function show(mode = 'add') {
        panel.style.display = 'flex';
        panel.style.flexDirection = 'column';
        onShow?.(mode);
    }
    function hide() {
        panel.style.display = 'none';
        onHide?.();
    }

    document.getElementById('btn-close')?.addEventListener('click', hide);
    document.getElementById('btn-cancel')?.addEventListener('click', hide);

    return { show, hide };
}

// ── Save button loading state ───────────────────────────────────────
function setSaving(saving) {
    const btn  = document.getElementById('btn-save');
    const spin = document.getElementById('save-spinner');
    const txt  = document.getElementById('save-text');
    if (!btn) return;
    btn.disabled = saving;
    if (spin) spin.style.display = saving ? 'inline-block' : 'none';
    if (txt)  txt.textContent    = saving ? 'Enregistrement…' : 'Enregistrer';
}

// ── KPI updater ─────────────────────────────────────────────────────
function updateKpis(kpis, mapping) {
    Object.entries(mapping).forEach(([key, id]) => {
        const el = document.getElementById(id);
        if (el && kpis[key] !== undefined) {
            el.textContent = Number(kpis[key]).toLocaleString('fr-FR');
        }
    });
}

// ── Row highlight (new/updated) ─────────────────────────────────────
function highlightRow(el) {
    if (!el) return;
    el.style.background = '#F0FDF4';
    el.style.transition = 'background 1.2s ease';
    setTimeout(() => { el.style.background = ''; }, 1500);
}

export { showToast, setupDeleteModal, setupPanel, setSaving, updateKpis, highlightRow, AVATAR_COLORS, csrf };
