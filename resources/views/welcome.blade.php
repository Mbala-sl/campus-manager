@extends('layouts.app')

@section('title', 'Campus Manager — Gestion des étudiants d\'université')

@push('styles')
<style>
    .nav-link { font-size:15px; font-weight:500; color:#1E293B; transition:color .2s ease; }
    .nav-link:hover { color:#16A34A; }
    .btn-primary { display:inline-flex; align-items:center; justify-content:center; background:#16A34A; color:#FFFFFF; font-weight:600; border-radius:14px; transition:transform .25s ease, box-shadow .25s ease; cursor:pointer; }
    .btn-primary:hover { transform:translateY(-2px); box-shadow:0 8px 24px rgba(22,163,74,.25); }
    .btn-outline { display:inline-flex; align-items:center; justify-content:center; gap:10px; background:transparent; border:2px solid #16A34A; color:#16A34A; font-weight:600; border-radius:14px; transition:background .2s ease; cursor:pointer; }
    .btn-outline:hover { background:#F0FDF4; }
    .feature-card { background:#FFFFFF; border-radius:20px; padding:28px; border:1px solid #F1F5F9; box-shadow:0 4px 16px rgba(15,23,42,.04); transition:transform .25s ease, box-shadow .25s ease; }
    .feature-card:hover { transform:translateY(-4px); box-shadow:0 12px 32px rgba(15,23,42,.08); }
    .section-badge { display:inline-flex; align-items:center; background:#DCFCE7; color:#15803D; font-size:13px; font-weight:600; padding:6px 16px; border-radius:999px; margin-bottom:20px; }
</style>
@endpush

@section('content')

<!-- ══ NAVBAR ══ -->
<header style="height:80px; background:rgba(255,255,255,0.96); border-bottom:1px solid #EDEDED; position:sticky; top:0; z-index:100; backdrop-filter:blur(8px);">
    <div style="max-width:1280px; margin:0 auto; padding:0 48px; height:100%; display:flex; justify-content:space-between; align-items:center;">
        <a href="/" style="display:flex; align-items:center; gap:14px;">
            <div style="width:48px; height:48px; border-radius:12px; background:#F0FDF4; border:1px solid #D1FAE5; display:flex; align-items:center; justify-content:center;">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none">
                    <path d="M12 3L1 9L12 15L21 10.09V17H23V9M5 13.18V17.18L12 21L19 17.18V13.18L12 17L5 13.18Z" fill="#16A34A"/>
                </svg>
            </div>
            <span style="font-size:24px; font-weight:700; color:#15803D;">Campus Manager</span>
        </a>
        <nav style="display:flex; align-items:center; gap:40px;">
            <a href="#features" class="nav-link">Fonctionnalités</a>
            <a href="#benefits" class="nav-link">Avantages</a>
            <a href="#security" class="nav-link">Sécurité</a>
            <a href="#pricing" class="nav-link">Tarifs</a>
            <a href="/dashboard" class="nav-link">Connexion</a>
        </nav>
        <a href="/dashboard" class="btn-primary" style="width:180px; height:48px; font-size:15px; border-radius:12px; box-shadow:0 4px 14px rgba(22,163,74,.18);">
            Essayer gratuitement
        </a>
    </div>
</header>

<!-- ══ HERO ══ -->
<section style="min-height:760px; padding:72px 0 120px; background:linear-gradient(180deg,#FFFFFF 0%,#F5FBF8 100%); position:relative; overflow:hidden;">
    <div style="position:absolute; bottom:0; left:0; right:0; height:220px; opacity:0.09; pointer-events:none;">
        <svg viewBox="0 0 1440 220" preserveAspectRatio="none" style="width:100%;height:100%;">
            <path d="M0,120 C360,40 720,200 1440,100 L1440,220 L0,220 Z" fill="#16A34A"/>
        </svg>
    </div>
    <div style="position:absolute; top:60px; right:-80px; width:520px; height:520px; border-radius:50%; background:radial-gradient(circle,#BBF7D0,transparent 70%); opacity:0.14; pointer-events:none;"></div>

    <div style="max-width:1280px; margin:0 auto; padding:0 48px; display:grid; grid-template-columns:45% 55%; gap:48px; align-items:center; position:relative; z-index:1;">
        <!-- Left -->
        <div style="max-width:540px;">
            <h1 style="font-size:64px; font-weight:800; line-height:72px; letter-spacing:-2px; color:#0F172A; margin:0;">
                Gestion des<br>étudiants d'université
            </h1>
            <p style="font-size:34px; font-weight:700; line-height:42px; color:#16A34A; margin:20px 0 0;">Simple. Sécurisé. Efficace.</p>
            <p style="font-size:19px; line-height:34px; color:#64748B; margin:28px 0 0; max-width:480px;">
                Une application complète pour gérer les étudiants, les filières et les inscriptions de votre université en toute sérénité.
            </p>
            <div style="display:flex; gap:20px; margin-top:42px; flex-wrap:wrap;">
                <a href="/dashboard" class="btn-primary" style="width:244px; height:58px; font-size:17px;">Commencer gratuitement</a>
                <a href="#demo" class="btn-outline" style="width:196px; height:58px; font-size:17px;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                        <circle cx="12" cy="12" r="10" stroke="#16A34A" stroke-width="2"/>
                        <polygon points="10,8 16,12 10,16" fill="#16A34A"/>
                    </svg>
                    Voir la démo
                </a>
            </div>
        </div>

        <!-- Right — Device Mockup -->
        <div style="position:relative; display:flex; justify-content:center; align-items:flex-start; padding-bottom:70px;">
            <!-- Laptop -->
            <div style="width:600px; filter:drop-shadow(0 30px 60px rgba(15,23,42,.14));">
                <div style="background:linear-gradient(160deg,#2d3748,#1a202c); border-radius:16px 16px 0 0; padding:10px 10px 0;">
                    <div style="width:6px;height:6px;border-radius:50%;background:#4A5568;margin:0 auto 6px;"></div>
                    <div style="background:#F8FAFC; border-radius:10px 10px 0 0; height:355px; overflow:hidden; display:flex;">
                        <!-- Mini sidebar -->
                        <div style="width:70px; background:#fff; border-right:1px solid #EAEFF5; padding:10px 8px; flex-shrink:0;">
                            <div style="width:34px;height:34px;border-radius:9px;background:linear-gradient(135deg,#22C55E,#16A34A);margin-bottom:14px;"></div>
                            <div style="height:7px;background:#DCFCE7;border-radius:3px;margin-bottom:7px;"></div>
                            <div style="height:7px;background:#F1F5F9;border-radius:3px;margin-bottom:5px;"></div>
                            <div style="height:7px;background:#F1F5F9;border-radius:3px;margin-bottom:5px;"></div>
                            <div style="height:7px;background:#F1F5F9;border-radius:3px;"></div>
                        </div>
                        <!-- Mini content -->
                        <div style="flex:1;padding:12px;overflow:hidden;">
                            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;">
                                <div>
                                    <div style="height:9px;background:#0F172A;border-radius:3px;width:80px;margin-bottom:4px;"></div>
                                    <div style="height:5px;background:#94A3B8;border-radius:2px;width:130px;"></div>
                                </div>
                                <div style="width:80px;height:26px;background:#F1F5F9;border-radius:7px;"></div>
                            </div>
                            <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:6px;margin-bottom:10px;">
                                @foreach([['#ECFDF5','#16A34A','1,248','+12%'],['#EFF6FF','#3B82F6','24','+2'],['#FFF7ED','#F97316','3,562','+17%'],['#F5F3FF','#8B5CF6','18','+2']] as $k)
                                <div style="background:#fff;border-radius:8px;padding:8px;box-shadow:0 1px 4px rgba(0,0,0,.05);">
                                    <div style="width:16px;height:16px;border-radius:4px;background:{{ $k[0] }};margin-bottom:5px;"></div>
                                    <div style="height:5px;background:#94A3B8;border-radius:2px;width:90%;margin-bottom:3px;"></div>
                                    <div style="height:10px;background:#0F172A;border-radius:2px;width:60%;margin-bottom:3px;"></div>
                                    <div style="height:5px;background:#DCFCE7;border-radius:2px;width:45%;"></div>
                                </div>
                                @endforeach
                            </div>
                            <div style="background:#fff;border-radius:10px;padding:10px;box-shadow:0 1px 4px rgba(0,0,0,.05);">
                                <div style="height:7px;background:#0F172A;border-radius:3px;width:120px;margin-bottom:9px;"></div>
                                <svg width="100%" height="145" viewBox="0 0 440 145" preserveAspectRatio="none">
                                    <defs>
                                        <linearGradient id="hcg" x1="0" y1="0" x2="0" y2="1">
                                            <stop offset="0%" stop-color="#16A34A" stop-opacity="0.15"/>
                                            <stop offset="100%" stop-color="#16A34A" stop-opacity="0"/>
                                        </linearGradient>
                                    </defs>
                                    <line x1="0" y1="36" x2="440" y2="36" stroke="#F1F5F9" stroke-width="1"/>
                                    <line x1="0" y1="72" x2="440" y2="72" stroke="#F1F5F9" stroke-width="1"/>
                                    <line x1="0" y1="108" x2="440" y2="108" stroke="#F1F5F9" stroke-width="1"/>
                                    <path d="M0,125 C60,105 100,115 150,80 C200,45 240,95 290,62 C330,35 380,50 440,18 L440,145 L0,145 Z" fill="url(#hcg)"/>
                                    <path d="M0,125 C60,105 100,115 150,80 C200,45 240,95 290,62 C330,35 380,50 440,18" stroke="#16A34A" stroke-width="2.5" fill="none" stroke-linecap="round"/>
                                    <circle cx="440" cy="18" r="4" fill="#16A34A"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
                <div style="background:linear-gradient(160deg,#4A5568,#2D3748);height:18px;border-radius:0 0 3px 3px;position:relative;">
                    <div style="position:absolute;bottom:0;left:50%;transform:translateX(-50%);width:170px;height:10px;background:#374151;border-radius:0 0 8px 8px;"></div>
                </div>
            </div>
            <!-- Phone -->
            <div style="position:absolute;bottom:-30px;right:20px;width:112px;height:206px;background:linear-gradient(160deg,#2d3748,#1a202c);border-radius:22px;padding:7px;box-shadow:0 24px 40px rgba(0,0,0,.22);">
                <div style="background:#F8FAFC;border-radius:16px;height:100%;overflow:hidden;padding:8px;">
                    <div style="background:linear-gradient(135deg,#22C55E,#16A34A);height:22px;border-radius:6px;margin-bottom:8px;display:flex;align-items:center;padding:0 7px;gap:4px;">
                        <div style="width:7px;height:7px;border-radius:50%;background:rgba(255,255,255,.6);"></div>
                        <div style="height:4px;background:rgba(255,255,255,.4);border-radius:2px;flex:1;"></div>
                    </div>
                    <div style="height:7px;background:#0F172A;border-radius:3px;width:75%;margin-bottom:4px;"></div>
                    <div style="height:4px;background:#94A3B8;border-radius:2px;width:55%;margin-bottom:10px;"></div>
                    @foreach([['#BBF7D0','44px'],['#BAE6FD','36px'],['#FDE68A','50px'],['#FEE2E2','38px']] as $p)
                    <div style="background:#fff;border-radius:7px;padding:6px;margin-bottom:4px;display:flex;align-items:center;gap:5px;box-shadow:0 1px 3px rgba(0,0,0,.05);">
                        <div style="width:17px;height:17px;border-radius:50%;background:{{ $p[0] }};flex-shrink:0;"></div>
                        <div style="flex:1;">
                            <div style="height:4px;background:#0F172A;border-radius:2px;width:{{ $p[1] }};margin-bottom:3px;"></div>
                            <div style="height:3px;background:#94A3B8;border-radius:2px;width:28px;"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ══ FEATURES ══ -->
<section id="features" style="padding:100px 0;background:#FFFFFF;">
    <div style="max-width:1280px;margin:0 auto;padding:0 48px;">
        <div style="text-align:center;margin-bottom:64px;">
            <div class="section-badge">Fonctionnalités complètes</div>
            <h2 style="font-size:46px;font-weight:800;color:#0F172A;margin:0;letter-spacing:-1.5px;line-height:56px;">
                Tout ce qu'il vous faut pour<br>gérer vos étudiants
            </h2>
            <p style="font-size:18px;color:#64748B;margin:20px auto 0;max-width:540px;line-height:32px;">
                Une solution qui couvre chaque aspect de la gestion universitaire.
            </p>
        </div>
        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:24px;">
            @php
            $features = [
                ['M12 20h9 M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z','#ECFDF5','#16A34A','Ajouter, modifier et supprimer un étudiant','Gérez les dossiers complets avec toutes les informations personnelles et académiques.'],
                ['M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z','#EFF6FF','#3B82F6','Associer un étudiant à une filière','Affectez facilement les étudiants à leurs filières et programmes d\'études.'],
                ['M21 21l-6-6m2-5a7 7 0 1 1-14 0 7 7 0 0 1 14 0z','#FFF7ED','#F97316','Rechercher et filtrer des étudiants','Retrouvez instantanément n\'importe quel étudiant grâce aux filtres avancés.'],
                ['M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z','#F5F3FF','#8B5CF6','Authentification et autorisation','Système de rôles complet avec accès sécurisé pour chaque type d\'utilisateur.'],
                ['M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2M9 5a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2M9 5a2 2 0 0 0 2-2h2a2 2 0 0 0 2 2','#FFF1F2','#F43F5E','Pagination des listes','Navigation fluide dans des milliers d\'étudiants avec une pagination intelligente.'],
                ['M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z M14 2v6h6','#F0FDF4','#22C55E','Gestion des filières','Créez et gérez toutes vos filières avec leurs paramètres spécifiques.'],
                ['M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2 M23 21v-2a4 4 0 0 0-3-3.87 M16 3.13a4 4 0 0 1 0 7.75','#FFF7ED','#F97316','Gestion des utilisateurs','Administrez les comptes admins et personnel avec leurs droits d\'accès.'],
                ['M18 20V10 M12 20V4 M6 20v-6','#EFF6FF','#3B82F6','Résultats affinés','Tableaux de bord analytiques avec graphiques pour piloter votre université.'],
            ];
            @endphp
            @foreach($features as [$icon,$bg,$ic,$title,$desc])
            <div class="feature-card">
                <div style="width:52px;height:52px;border-radius:14px;background:{{ $bg }};display:flex;align-items:center;justify-content:center;margin-bottom:20px;">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="{{ $ic }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="{{ $icon }}"/></svg>
                </div>
                <h3 style="font-size:16px;font-weight:700;color:#0F172A;margin:0 0 10px;line-height:24px;">{{ $title }}</h3>
                <p style="font-size:14px;color:#64748B;line-height:23px;margin:0;">{{ $desc }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ══ BENEFITS ══ -->
<section id="benefits" style="padding:100px 0;background:#F8FAFC;">
    <div style="max-width:1280px;margin:0 auto;padding:0 48px;">
        <div style="text-align:center;margin-bottom:64px;">
            <div class="section-badge">Pourquoi nous choisir</div>
            <h2 style="font-size:44px;font-weight:800;color:#0F172A;margin:0;letter-spacing:-1.5px;line-height:54px;">
                Une solution conçue pour<br>votre réussite
            </h2>
        </div>
        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:32px;text-align:center;">
            @foreach([['🔒','#ECFDF5','Sécurité','Données chiffrées et accès protégés pour toutes vos informations sensibles.'],['⚡','#FFF7ED','Rapidité','Interface optimisée pour des opérations ultra-rapides même avec des milliers d\'étudiants.'],['🌐','#EFF6FF','Accessibilité','Accessible depuis n\'importe quel appareil, navigateur et connexion internet.'],['📈','#F5F3FF','Fiabilité','Disponibilité 99.9%, sauvegardes automatiques et support réactif.']] as [$emoji,$bg,$title,$desc])
            <div>
                <div style="width:72px;height:72px;border-radius:20px;background:{{ $bg }};display:flex;align-items:center;justify-content:center;font-size:30px;margin:0 auto 20px;">{{ $emoji }}</div>
                <h3 style="font-size:20px;font-weight:700;color:#0F172A;margin:0 0 12px;">{{ $title }}</h3>
                <p style="font-size:15px;color:#64748B;line-height:26px;margin:0;">{{ $desc }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ══ CTA BANNER ══ -->
<section style="padding:88px 0;background:linear-gradient(135deg,#16A34A 0%,#15803D 100%);position:relative;overflow:hidden;">
    <div style="position:absolute;top:-60px;right:-60px;width:320px;height:320px;border-radius:50%;background:rgba(255,255,255,.06);pointer-events:none;"></div>
    <div style="position:absolute;bottom:-80px;left:-40px;width:400px;height:400px;border-radius:50%;background:rgba(255,255,255,.04);pointer-events:none;"></div>
    <div style="max-width:1280px;margin:0 auto;padding:0 48px;text-align:center;position:relative;z-index:1;">
        <h2 style="font-size:44px;font-weight:800;color:#FFFFFF;margin:0 0 16px;letter-spacing:-1.5px;line-height:54px;">
            Prêt à simplifier la gestion<br>de vos étudiants ?
        </h2>
        <p style="font-size:18px;color:rgba(255,255,255,.82);margin:0 auto 40px;max-width:500px;line-height:32px;">
            Rejoignez les universités qui font confiance à Campus Manager.
        </p>
        <div style="display:flex;gap:16px;justify-content:center;">
            <a href="/dashboard" style="display:inline-flex;align-items:center;justify-content:center;background:#FFFFFF;color:#15803D;font-size:17px;font-weight:700;border-radius:14px;padding:0 36px;height:58px;box-shadow:0 8px 24px rgba(0,0,0,.12);">Commencer gratuitement</a>
            <a href="/students" style="display:inline-flex;align-items:center;justify-content:center;background:rgba(255,255,255,.15);color:#FFFFFF;font-size:17px;font-weight:600;border-radius:14px;padding:0 36px;height:58px;border:2px solid rgba(255,255,255,.3);">Voir une démo</a>
        </div>
    </div>
</section>

<!-- ══ FOOTER ══ -->
<footer style="background:#0F172A;padding:64px 0 32px;">
    <div style="max-width:1280px;margin:0 auto;padding:0 48px;">
        <div style="display:grid;grid-template-columns:2fr 1fr 1fr 1fr;gap:48px;margin-bottom:48px;">
            <div>
                <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;">
                    <div style="width:40px;height:40px;border-radius:10px;background:#16A34A;display:flex;align-items:center;justify-content:center;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none"><path d="M12 3L1 9L12 15L21 10.09V17H23V9M5 13.18V17.18L12 21L19 17.18V13.18L12 17L5 13.18Z" fill="white"/></svg>
                    </div>
                    <span style="font-size:20px;font-weight:700;color:#FFFFFF;">Campus Manager</span>
                </div>
                <p style="font-size:14px;color:#94A3B8;line-height:24px;max-width:280px;margin:0 0 24px;">La solution complète pour la gestion universitaire moderne. Simple, sécurisée et efficace.</p>
            </div>
            @foreach([['Fonctionnalités',['Gestion étudiants','Gestion filières','Inscriptions','Statistiques','Exportation']],['Ressources',['Documentation','Guides','API Reference','Changelog']],['Légal',['Confidentialité','Conditions d\'utilisation','Cookies','Contact']]] as [$title,$links])
            <div>
                <h4 style="font-size:12px;font-weight:700;color:#FFFFFF;margin:0 0 20px;text-transform:uppercase;letter-spacing:.5px;">{{ $title }}</h4>
                <ul style="list-style:none;margin:0;padding:0;display:flex;flex-direction:column;gap:12px;">
                    @foreach($links as $link)
                    <li><a href="#" style="font-size:14px;color:#94A3B8;">{{ $link }}</a></li>
                    @endforeach
                </ul>
            </div>
            @endforeach
        </div>
        <div style="border-top:1px solid #1E293B;padding-top:28px;display:flex;justify-content:space-between;align-items:center;">
            <p style="font-size:14px;color:#475569;margin:0;">© 2025 Campus Manager. Tous droits réservés.</p>
            <p style="font-size:14px;color:#475569;margin:0;">Conçu avec ❤ pour l'éducation</p>
        </div>
    </div>
</footer>

@endsection
