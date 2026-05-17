<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Campus Manager')</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    {{-- Protection BFCache : si le navigateur restaure cette page depuis son cache mémoire
         (bouton Retour après déconnexion), on force un rechargement immédiat.
         Le serveur vérifie alors l'auth et redirige vers la landing page. --}}
    <script>
        window.addEventListener('pageshow', function (e) {
            if (e.persisted) {
                // Page restaurée depuis le BFCache → recharger pour déclencher la vérification auth côté serveur
                window.location.reload();
            }
        });
        // Compatibilité performance.navigation (anciens navigateurs)
        if (window.performance && window.performance.navigation &&
            window.performance.navigation.type === 2) {
            window.location.reload();
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html, body { height: 100%; }
        body { font-family: 'Inter', ui-sans-serif, system-ui, sans-serif; background: #F8FAFC; color: #0F172A; }
        a { text-decoration: none; color: inherit; }
        .hidden { display: none !important; }
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #E2E8F0; border-radius: 3px; }

        /* ── Sidebar nav items ── */
        .sidebar-item {
            display: flex; align-items: center; gap: 11px;
            height: 46px; padding: 0 14px;
            border-radius: 12px;
            font-size: 13.5px; font-weight: 500; color: #475569;
            cursor: pointer;
            transition: background .16s, color .16s;
            position: relative; white-space: nowrap;
        }
        .sidebar-item:hover  { background: #F1F5F9; color: #0F172A; }
        .sidebar-item.active { background: #DCFCE7; color: #15803D; font-weight: 600; }
        .sidebar-item.active::before {
            content: ''; position: absolute; left: 0; top: 50%;
            transform: translateY(-50%);
            width: 3px; height: 20px; background: #22C55E; border-radius: 0 999px 999px 0;
        }
        .sidebar-icon { flex-shrink: 0; opacity: .75; }
        .sidebar-item.active .sidebar-icon,
        .sidebar-item:hover  .sidebar-icon { opacity: 1; }

        /* ── Locked items ── */
        .sidebar-item-locked {
            display: flex; align-items: center; gap: 11px;
            height: 46px; padding: 0 14px;
            border-radius: 12px;
            font-size: 13.5px; font-weight: 500;
            color: #94A3B8; cursor: pointer;
            opacity: 0.55;
            transition: opacity .16s, background .16s;
            white-space: nowrap;
        }
        .sidebar-item-locked:hover { opacity: 0.75; background: #F8FAFC; }
        .sidebar-section-label {
            font-size: 10px; font-weight: 700; color: #CBD5E1;
            letter-spacing: 1px; text-transform: uppercase;
            padding: 0 14px; margin: 18px 0 6px;
        }
    </style>
    @stack('styles')
</head>
<body>

<div style="display:flex; height:100vh; overflow:hidden;">

    <!-- ══════════ SIDEBAR ══════════ -->
    <aside style="position:fixed; top:0; left:0; width:268px; height:100vh; background:#FFFFFF; border-right:1px solid #EAEFF5; display:flex; flex-direction:column; z-index:100; overflow-y:auto; overflow-x:hidden;">

        <!-- Logo -->
        <div style="padding:18px 18px 14px; display:flex; align-items:center; gap:12px; border-bottom:1px solid #F1F5F9; flex-shrink:0;">
            <div style="width:42px; height:42px; border-radius:12px; background:linear-gradient(135deg,#22C55E,#16A34A); display:flex; align-items:center; justify-content:center; flex-shrink:0; box-shadow:0 6px 14px rgba(34,197,94,.2);">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                    <path d="M12 3L1 9L12 15L21 10.09V17H23V9M5 13.18V17.18L12 21L19 17.18V13.18L12 17L5 13.18Z" fill="white"/>
                </svg>
            </div>
            <div style="min-width:0;">
                <div style="font-size:15px; font-weight:700; color:#0F172A; white-space:nowrap;">Campus Manager</div>
                <div style="font-size:11px; color:#94A3B8; margin-top:1px;">University Management</div>
            </div>
        </div>

        <!-- Navigation -->
        <nav style="padding:8px 10px; flex:1; overflow-y:auto;">

            <!-- ── Gestion académique ── -->
            <div class="sidebar-section-label">Gestion académique</div>

            <a href="{{ route('dashboard') }}" class="sidebar-item @yield('nav_dashboard')">
                <svg class="sidebar-icon" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
                    <rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>
                </svg>
                Tableau de bord
            </a>

            <a href="{{ route('students.index') }}" class="sidebar-item @yield('nav_students')" style="margin-top:2px;">
                <svg class="sidebar-icon" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
                Étudiants
            </a>

            <a href="{{ route('filieres.index') }}" class="sidebar-item @yield('nav_filieres')" style="margin-top:2px;">
                <svg class="sidebar-icon" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
                    <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
                </svg>
                Filières
            </a>

            <a href="{{ route('inscriptions.index') }}" class="sidebar-item @yield('nav_inscriptions')" style="margin-top:2px;">
                <svg class="sidebar-icon" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                    <line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/>
                </svg>
                Inscriptions
            </a>

            <a href="{{ route('annees.index') }}" class="sidebar-item @yield('nav_annees')" style="margin-top:2px;">
                <svg class="sidebar-icon" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="4" width="18" height="18" rx="2"/>
                    <line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                </svg>
                Années académiques
            </a>

            <!-- ── Administration (verrouillée) ── -->
            <div class="sidebar-section-label">Administration</div>

            @foreach([
                [route('utilisateurs'), 'Utilisateurs', 'M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2 M12 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8z'],
                [route('paiements'),    'Paiements',    'M12 1v22 M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6'],
                [route('exportations'), 'Exportations', 'M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4 M7 10l5 5 5-5 M12 15V3'],
                [route('journal'),      'Journal d\'activité', 'M12 20h9 M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z'],
            ] as [$url, $label, $icon])
            <a href="{{ $url }}" class="sidebar-item-locked" style="margin-top:2px;">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;">
                    <path d="{{ $icon }}"/>
                </svg>
                <span style="flex:1; min-width:0; overflow:hidden; text-overflow:ellipsis;">{{ $label }}</span>
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" style="flex-shrink:0; margin-left:auto;">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                </svg>
            </a>
            @endforeach

            <!-- ── Autres (verrouillés) ── -->
            <div class="sidebar-section-label">Autres</div>

            @foreach([
                [route('statistiques'),  'Statistiques',  'M18 20V10 M12 20V4 M6 20v-6'],
                [route('notifications'), 'Notifications', 'M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9 M13.73 21a2 2 0 0 1-3.46 0'],
                [route('parametres'),    'Paramètres',    'M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z M12 8v4 M12 16h.01'],
            ] as [$url, $label, $icon])
            <a href="{{ $url }}" class="sidebar-item-locked" style="margin-top:2px;">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;">
                    <path d="{{ $icon }}"/>
                </svg>
                <span style="flex:1; min-width:0; overflow:hidden; text-overflow:ellipsis;">{{ $label }}</span>
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" style="flex-shrink:0; margin-left:auto;">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                </svg>
            </a>
            @endforeach
        </nav>

        <!-- Bas de sidebar : aide uniquement -->
        <div style="padding:12px 10px; border-top:1px solid #F1F5F9; flex-shrink:0;">
            <a href="#" style="display:flex; align-items:center; gap:10px; background:#F0FDF4; border-radius:12px; padding:12px 14px; text-decoration:none;">
                <div style="width:30px; height:30px; border-radius:8px; background:#DCFCE7; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#16A34A" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                </div>
                <div style="min-width:0;">
                    <div style="font-size:12px; font-weight:600; color:#15803D;">Besoin d'aide ?</div>
                    <div style="font-size:11px; color:#16A34A; opacity:.7; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">Consultez la documentation</div>
                </div>
            </a>
        </div>
    </aside>

    <!-- ══════════ MAIN ══════════ -->
    <div style="margin-left:268px; width:calc(100% - 268px); height:100vh; overflow-y:auto; display:flex; flex-direction:column;">
        @yield('content')
    </div>

</div>

@stack('scripts')
</body>
</html>
