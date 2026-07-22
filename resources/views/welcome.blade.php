<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WasteMove — Gestion des déchets dans les communes de Dakar</title>
    <meta name="description" content="WasteMove, la plateforme citoyenne pour signaler, suivre et améliorer la gestion des déchets dans les 19 communes de Dakar.">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=sora:600,700,800|inter:400,500,600,700" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --bg: #FBF6EC;
            --bg-soft: #F4ECDA;
            --white: #FFFFFF;
            --ink: #2B2118;
            --ink-soft: #6E5B47;
            --line: rgba(43, 33, 24, 0.10);

            --green: #168039;
            --green-deep: #0F5C29;
            --green-soft: #DCEFE0;
            --gold: #E8A317;
            --gold-deep: #B8790E;
            --gold-soft: #FCEFD2;
            --terracotta: #D1603D;
            --terracotta-deep: #A8472A;
            --terracotta-soft: #F8E3DA;

            --font-display: 'Sora', ui-sans-serif, system-ui, sans-serif;
            --font-body: 'Inter', ui-sans-serif, system-ui, sans-serif;
        }

        html { scroll-behavior: smooth; }
        body { font-family: var(--font-body); background: var(--bg); color: var(--ink-soft); }
        h1, h2, h3, .font-display { font-family: var(--font-display); color: var(--ink); }

        section[id] { scroll-margin-top: 5.5rem; }

        :focus-visible { outline: 2px solid var(--green); outline-offset: 3px; border-radius: 4px; }

        .skip-link {
            position: absolute; left: -999px; top: 1rem; z-index: 100;
            background: var(--green); color: white; padding: 0.6rem 1rem;
            border-radius: 0.5rem; font-size: 0.875rem;
        }
        .skip-link:focus { left: 1rem; }

        #scroll-progress {
            position: fixed; top: 0; left: 0; height: 3px; width: 0%;
            background: linear-gradient(90deg, var(--green), var(--gold), var(--terracotta));
            z-index: 70; transition: width 0.1s ease-out;
        }

        /* Subtle animated colour wash + dot grid */
        .bg-texture {
            position: fixed; inset: 0; z-index: -1; pointer-events: none;
            background-color: var(--bg);
            background-image: radial-gradient(rgba(43,33,24,0.07) 1px, transparent 1px);
            background-size: 24px 24px;
        }
        .blob {
            position: absolute; filter: blur(60px); opacity: 0.55; z-index: 0;
            animation: blob-morph 14s ease-in-out infinite;
        }
        @keyframes blob-morph {
            0%, 100% { border-radius: 42% 58% 64% 36% / 45% 45% 55% 55%; transform: scale(1) translate(0,0); }
            33% { border-radius: 60% 40% 35% 65% / 55% 65% 35% 45%; transform: scale(1.08) translate(10px, -10px); }
            66% { border-radius: 35% 65% 55% 45% / 40% 35% 65% 60%; transform: scale(0.96) translate(-8px, 8px); }
        }

        .nav-shell { background: rgba(251, 246, 236, 0.82); backdrop-filter: blur(16px); border-bottom: 1px solid var(--line); }
        .nav-link { position: relative; color: var(--ink-soft); padding: 0.4rem 0.1rem; transition: color 0.2s ease; }
        .nav-link::after { content: ''; position: absolute; left: 0; bottom: -2px; height: 2px; width: 0; background: var(--green); transition: width 0.25s ease; border-radius: 2px; }
        .nav-link:hover { color: var(--ink); }
        .nav-link.active { color: var(--ink); }
        .nav-link.active::after { width: 100%; }

        .btn-primary {
            background: linear-gradient(120deg, var(--green), var(--green-deep));
            color: white; font-weight: 600; transition: transform 0.25s ease, box-shadow 0.25s ease;
            box-shadow: 0 10px 24px -10px rgba(22, 128, 57, 0.45);
        }
        .btn-primary:hover { transform: translateY(-2px) scale(1.02); box-shadow: 0 16px 30px -10px rgba(22, 128, 57, 0.55); }

        .status-chip {
            display: inline-flex; align-items: center; gap: 0.45rem; font-size: 0.78rem; font-weight: 600;
            color: var(--green-deep); background: var(--green-soft); border: 1px solid rgba(22,128,57,0.18);
            padding: 0.35rem 0.75rem; border-radius: 999px;
        }
        .pulse-dot { width: 6px; height: 6px; border-radius: 50%; background: var(--green); animation: pulse-ring 2.2s infinite; }
        @keyframes pulse-ring {
            0% { box-shadow: 0 0 0 0 rgba(22, 128, 57, 0.45); }
            70% { box-shadow: 0 0 0 7px rgba(22, 128, 57, 0); }
            100% { box-shadow: 0 0 0 0 rgba(22, 128, 57, 0); }
        }

        #mobile-menu {
            position: fixed; inset: 0; background: var(--bg); z-index: 60;
            display: flex; flex-direction: column; padding: 1.5rem;
            transform: translateY(-100%); transition: transform 0.35s ease;
        }
        #mobile-menu.open { transform: translateY(0); }
        #mobile-menu a { font-family: var(--font-display); font-size: 1.6rem; color: var(--ink); font-weight: 700; }

        /* Duotone photo signature (vert / or) */
        .photo-frame { position: relative; border-radius: 32px; overflow: hidden; border: 4px solid var(--white); box-shadow: 0 20px 45px -20px rgba(43,33,24,0.25); }
        .photo-frame img { display: block; width: 100%; height: 100%; object-fit: cover; filter: saturate(1.1); transition: transform 0.6s ease; }
        .photo-frame:hover img { transform: scale(1.05); }
        .photo-frame::after {
            content: ''; position: absolute; inset: 0;
            background: linear-gradient(165deg, rgba(22,128,57,0.30), rgba(232,163,23,0.22));
            mix-blend-mode: multiply;
        }
        .photo-tag {
            position: absolute; top: 14px; left: 14px; z-index: 2;
            background: var(--white); color: var(--ink); font-family: var(--font-display); font-weight: 700;
            font-size: 0.78rem; padding: 0.4rem 0.85rem; border-radius: 999px; box-shadow: 0 8px 18px -8px rgba(43,33,24,0.25);
        }

        .glass-card {
            background: rgba(255,255,255,0.88); border: 1px solid var(--line); backdrop-filter: blur(10px);
            box-shadow: 0 14px 30px -14px rgba(43,33,24,0.18);
            animation: float-card 5s ease-in-out infinite;
        }
        .glass-card.delay { animation-delay: 1.2s; }
        @keyframes float-card { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-8px); } }

        .card-hover { transition: transform 0.3s ease, box-shadow 0.3s ease; }
        .card-hover:hover { transform: translateY(-5px); box-shadow: 0 20px 40px -18px rgba(43,33,24,0.18); }

        .eyebrow { font-weight: 700; font-size: 0.78rem; letter-spacing: 0.06em; color: var(--green-deep); text-transform: uppercase; }
        .eyebrow-light { font-weight: 700; font-size: 0.78rem; letter-spacing: 0.06em; color: var(--gold-soft); text-transform: uppercase; }

        .scroll-reveal { opacity: 0; transform: translateY(22px); transition: opacity 0.7s ease, transform 0.7s ease; }
        .scroll-reveal.revealed { opacity: 1; transform: translateY(0); }

        /* Marquee photo strip */
        .marquee-track { display: flex; gap: 1.25rem; width: max-content; animation: marquee-scroll 32s linear infinite; }
        @keyframes marquee-scroll { from { transform: translateX(0); } to { transform: translateX(-50%); } }
        .marquee-item { width: 230px; height: 160px; border-radius: 20px; overflow: hidden; flex-shrink: 0; border: 3px solid var(--white); box-shadow: 0 10px 24px -12px rgba(43,33,24,0.22); }
        .marquee-item img { width: 100%; height: 100%; object-fit: cover; }
        .marquee-wrap:hover .marquee-track { animation-play-state: paused; }

        @media (prefers-reduced-motion: reduce) {
            html { scroll-behavior: auto; }
            .pulse-dot, .scroll-reveal, .btn-primary, .blob, .glass-card, .marquee-track, .photo-frame img {
                animation: none !important; transition: none !important; opacity: 1 !important; transform: none !important;
            }
        }
    </style>
</head>
<body class="antialiased">

    <a href="#contenu" class="skip-link">Aller au contenu</a>
    <div id="scroll-progress"></div>
    <div class="bg-texture"></div>

    {{-- Navbar --}}
    <nav class="nav-shell fixed top-0 left-0 right-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <a href="/" class="flex items-center gap-2.5">
                    <img src="{{ asset('images/logo.png') }}" alt="WasteMove" class="h-7 w-auto">
                    <span class="font-display text-lg font-extrabold text-[var(--ink)]">WasteMove</span>
                </a>

                <div class="hidden lg:flex items-center gap-28">
                    <a href="#accueil" class="nav-link active font-bold text-3xl tracking-wide" data-nav>Accueil</a>
                    <a href="#a-propos" class="nav-link font-bold text-3xl tracking-wide" data-nav>À propos</a>
                </div>

                <div class="hidden lg:flex items-center gap-3">
                    <a href="{{ route('login') }}" class="px-5 py-2.5 text-sm font-semibold text-[var(--ink-soft)] hover:text-[var(--green-deep)] transition-colors border border-[var(--line)] rounded-xl hover:border-[var(--green)]">Connexion</a>
                    <a href="{{ route('register') }}" class="btn-primary px-5 py-2.5 text-sm rounded-xl">Créer un compte</a>
                </div>

                <button id="menu-toggle" aria-label="Ouvrir le menu" aria-expanded="false" class="lg:hidden text-[var(--ink)] p-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 7h16M4 12h16M4 17h16"/></svg>
                </button>
            </div>
        </div>
    </nav>

    {{-- Mobile menu --}}
    <div id="mobile-menu">
        <div class="flex items-center justify-between mb-10">
            <span class="font-display text-lg font-extrabold text-[var(--ink)]">WasteMove</span>
            <button id="menu-close" aria-label="Fermer le menu" class="text-[var(--ink)] p-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="flex flex-col gap-6">
            <a href="#accueil" class="text-3xl font-bold">Accueil</a>
            <a href="#a-propos" class="text-3xl font-bold">À propos</a>
        </div>
        <div class="mt-auto flex flex-col gap-3 pt-10">
            <a href="{{ route('login') }}" class="px-5 py-3 text-center text-[var(--ink)] border border-[var(--line)] rounded-xl">Connexion</a>
            <a href="{{ route('register') }}" class="btn-primary px-5 py-3 text-center rounded-xl">Créer un compte</a>
        </div>
    </div>

    <main id="contenu">
    {{-- Hero --}}
    <section id="accueil" class="relative min-h-screen flex items-center pt-16 overflow-hidden">
        <div class="blob w-[420px] h-[420px] bg-[var(--green-soft)] -top-16 -right-10"></div>
        <div class="blob w-[340px] h-[340px] bg-[var(--gold-soft)] bottom-10 left-0" style="animation-delay: 4s;"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24 w-full">
            <div class="grid lg:grid-cols-2 gap-14 lg:gap-16 items-center">
                <div class="space-y-7">
                    <div class="status-chip"><span class="pulse-dot"></span> Plateforme de gestion des déchets à Dakar</div>
                    <h1 class="font-display text-4xl sm:text-5xl lg:text-[3.3rem] font-extrabold leading-[1.1]">
                        WasteMove<br><span class="text-[var(--green)]">Pour un Dakar plus propre.</span>
                    </h1>
                    <p class="text-lg leading-relaxed max-w-lg">
                        Signalez, suivez et participez à la gestion des déchets dans les <strong class="text-[var(--ink)]">19 communes</strong> de Dakar. WasteMove connecte chaque citoyen à l'agent le plus proche pour une intervention rapide et efficace.
                    </p>
                    <p class="text-sm italic" style="color: var(--gold-deep);">« Dalal ak diam » — la propreté, c'est l'affaire de tous.</p>
                    <div class="flex flex-wrap gap-4">
                        <a href="{{ route('register') }}" class="btn-primary group px-7 py-3.5 rounded-2xl">
                            Signaler un dépôt
                            <svg class="inline w-4 h-4 ml-1.5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </a>
                        <a href="#a-propos" class="px-7 py-3.5 text-[var(--ink)] font-semibold rounded-2xl border border-[var(--line)] bg-white hover:shadow-md transition-all">
                            Découvrir WasteMove
                        </a>
                    </div>
                    <div class="flex items-center gap-4 pt-2">
                        <div class="flex -space-x-2">
                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-[var(--green)] to-[var(--green-deep)] border-2 border-white flex items-center justify-center text-white text-xs font-bold">A</div>
                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-[var(--gold)] to-[var(--gold-deep)] border-2 border-white flex items-center justify-center text-white text-xs font-bold">B</div>
                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-[var(--terracotta)] to-[var(--terracotta-deep)] border-2 border-white flex items-center justify-center text-white text-xs font-bold">C</div>
                        </div>
                        <p class="text-sm"><span class="font-bold text-[var(--ink)]">+{{ $agents_display }}</span> agents mobilisés dans les 19 communes</p>
                    </div>
                </div>

                <div class="relative">
                    <div class="photo-frame aspect-[4/3]">
                        <img src="https://images.unsplash.com/photo-1690323223790-4df744a1a033?w=900&q=80" alt="Vue panoramique de la ville de Dakar, Sénégal">
                    </div>
                    <div class="absolute -bottom-7 -left-4 sm:-left-7 glass-card rounded-2xl p-4 max-w-[210px]">
                        <p class="text-xs font-semibold text-[var(--ink-soft)] mb-1">Taux de résolution</p>
                        <p class="font-display text-xl font-extrabold text-[var(--ink)]">{{ $taux_resolution }}%</p>
                    </div>
                    <div class="absolute -top-6 -right-3 sm:-right-6 glass-card delay rounded-2xl p-4">
                        <p class="text-xs font-semibold text-[var(--ink-soft)] mb-1">Satisfaction</p>
                        <p class="font-display text-xl font-extrabold text-[var(--green)]">{{ $satisfaction }}%</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Marquee: Senegal photo strip --}}
    <section class="relative bg-white/60 border-y border-[var(--line)] py-8 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-5">
            <p class="eyebrow">Conçu pour les villes du Sénégal</p>
        </div>
        <div class="marquee-wrap overflow-hidden">
            <div class="marquee-track">
                <div class="marquee-item"><img src="https://images.unsplash.com/photo-1690323223790-4df744a1a033?w=460&q=70" alt="Skyline de Dakar"></div>
                <div class="marquee-item"><img src="https://images.unsplash.com/photo-1648504735627-6af97e8337a9?w=460&q=70" alt="Pirogues colorées sur la côte sénégalaise"></div>
                <div class="marquee-item"><img src="https://images.unsplash.com/photo-1611258490565-4a06c019e631?w=460&q=70" alt="Architecture moderne en bord de mer à Dakar"></div>
                <div class="marquee-item"><img src="https://images.unsplash.com/photo-1773771795720-6c7b6626c4a5?w=460&q=70" alt="Baie calme près de Dakar"></div>
                <div class="marquee-item"><img src="https://images.unsplash.com/photo-1659291816236-c8ab734984a4?w=460&q=70" alt="Motif coloré inspiré du Sénégal"></div>
                <div class="marquee-item"><img src="https://images.unsplash.com/photo-1690323223790-4df744a1a033?w=460&q=70" alt="Skyline de Dakar"></div>
                <div class="marquee-item"><img src="https://images.unsplash.com/photo-1648504735627-6af97e8337a9?w=460&q=70" alt="Pirogues colorées sur la côte sénégalaise"></div>
                <div class="marquee-item"><img src="https://images.unsplash.com/photo-1611258490565-4a06c019e631?w=460&q=70" alt="Architecture moderne en bord de mer à Dakar"></div>
                <div class="marquee-item"><img src="https://images.unsplash.com/photo-1773771795720-6c7b6626c4a5?w=460&q=70" alt="Baie calme près de Dakar"></div>
                <div class="marquee-item"><img src="https://images.unsplash.com/photo-1659291816236-c8ab734984a4?w=460&q=70" alt="Motif coloré inspiré du Sénégal"></div>
            </div>
        </div>
    </section>

    {{-- Stats bar --}}
    <section id="chiffres" class="relative bg-white/60 border-y border-[var(--line)]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
                <div class="rounded-2xl p-5 text-center" style="background: var(--green-soft);">
                    <p class="font-display text-2xl sm:text-3xl font-extrabold text-[var(--green-deep)]"><span data-count="{{ $signalements }}">0</span>+</p>
                    <p class="text-xs sm:text-sm text-[var(--ink-soft)] mt-1">Signalements traités</p>
                </div>
                <div class="rounded-2xl p-5 text-center" style="background: var(--gold-soft);">
                    <p class="font-display text-2xl sm:text-3xl font-extrabold text-[var(--gold-deep)]"><span data-count="{{ $agents }}">0</span>+</p>
                    <p class="text-xs sm:text-sm text-[var(--ink-soft)] mt-1">Agents mobilisés</p>
                </div>
                <div class="rounded-2xl p-5 text-center" style="background: var(--terracotta-soft);">
                    <p class="font-display text-2xl sm:text-3xl font-extrabold text-[var(--terracotta-deep)]"><span data-count="{{ $zones }}">0</span></p>
                    <p class="text-xs sm:text-sm text-[var(--ink-soft)] mt-1">Zones couvertes</p>
                </div>
                <div class="rounded-2xl p-5 text-center" style="background: var(--green-soft);">
                    <p class="font-display text-2xl sm:text-3xl font-extrabold text-[var(--green-deep)]"><span data-count="{{ $taux_resolution }}">0</span>%</p>
                    <p class="text-xs sm:text-sm text-[var(--ink-soft)] mt-1">Taux de résolution</p>
                </div>
            </div>
        </div>
    </section>

    {{-- À propos: regroupe le problème, les fonctionnalités et le fonctionnement --}}
    <section id="a-propos" class="relative py-20 lg:py-28">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Intro --}}
            <div class="max-w-2xl mb-16">
                <span class="eyebrow">À propos</span>
                <h2 class="font-display text-3xl sm:text-4xl font-extrabold mt-2">Pourquoi WasteMove existe</h2>
                <p class="mt-4 text-lg leading-relaxed">
                    À Dakar, la gestion des déchets est un défi quotidien pour les <strong class="text-[var(--ink)]">19 communes</strong>. WasteMove rend chaque signalement visible et traçable, apporte de la <strong class="text-[var(--ink)]">transparence</strong> dans le traitement des dépôts sauvages, donne aux citoyens un rôle actif grâce à l'<strong class="text-[var(--ink)]">inclusion citoyenne</strong>, et fournit aux municipalités les données nécessaires pour une <strong class="text-[var(--ink)]">efficacité</strong> accrue sur le terrain.
                </p>
            </div>

            {{-- Sub-section: Le constat --}}
            <div class="mb-24" id="le-constat">
                <h3 class="font-display text-2xl font-extrabold mb-8">Le constat</h3>
                <div class="grid md:grid-cols-3 gap-6 lg:gap-8">
                    <div class="rounded-3xl p-8 shadow-sm border border-[var(--line)] card-hover scroll-reveal" style="background: var(--terracotta-soft);">
                        <div class="w-12 h-12 rounded-2xl bg-white/70 flex items-center justify-center mb-5">
                            <svg class="w-6 h-6 text-[var(--terracotta-deep)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </div>
                        <h4 class="font-display text-lg font-bold mb-3">Dépôts sauvages non signalés</h4>
                        <p class="text-sm leading-relaxed">Un citoyen voit un dépôt sauvage mais ne sait pas à qui le signaler. L'information circule mal, les déchets s'accumulent.</p>
                    </div>
                    <div class="rounded-3xl p-8 shadow-sm border border-[var(--line)] card-hover scroll-reveal" style="background: var(--gold-soft);">
                        <div class="w-12 h-12 rounded-2xl bg-white/70 flex items-center justify-center mb-5">
                            <svg class="w-6 h-6 text-[var(--gold-deep)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h4 class="font-display text-lg font-bold mb-3">Collecte inefficace</h4>
                        <p class="text-sm leading-relaxed">Aucune coordination entre les équipes de collecte et les besoins réels : des zones sont oubliées, d'autres sur-servies.</p>
                    </div>
                    <div class="rounded-3xl p-8 shadow-sm border border-[var(--line)] card-hover scroll-reveal" style="background: var(--green-soft);">
                        <div class="w-12 h-12 rounded-2xl bg-white/70 flex items-center justify-center mb-5">
                            <svg class="w-6 h-6 text-[var(--green-deep)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <h4 class="font-display text-lg font-bold mb-3">Aucune donnée exploitable</h4>
                        <p class="text-sm leading-relaxed">Sans tableau de bord, impossible de savoir quelles communes concentrent les dépôts ou si le service s'améliore.</p>
                    </div>
                </div>
            </div>

            {{-- Sub-section: Fonctionnalités --}}
            <div class="mb-24" id="fonctionnalites">
                <h3 class="font-display text-2xl font-extrabold mb-8">Fonctionnalités</h3>
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                    <div class="bg-white rounded-3xl p-8 shadow-sm card-hover scroll-reveal">
                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center mb-4" style="background: var(--green-soft);">
                            <svg class="w-6 h-6 text-[var(--green-deep)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><circle cx="12" cy="13" r="3.5"/></svg>
                        </div>
                        <h4 class="font-display text-lg font-bold mb-2">Signalement géolocalisé</h4>
                        <p class="text-sm">Une photo du dépôt, votre position et une courte description. L'agent reçoit le dossier complet en 30 secondes.</p>
                    </div>
                    <div class="bg-white rounded-3xl p-8 shadow-sm card-hover scroll-reveal">
                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center mb-4" style="background: var(--gold-soft);">
                            <svg class="w-6 h-6 text-[var(--gold-deep)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <h4 class="font-display text-lg font-bold mb-2">Suivi en temps réel</h4>
                        <p class="text-sm">Chaque signalement apparaît sur la carte de Dakar avec son statut, sa commune et sa zone d'intervention.</p>
                    </div>
                    <div class="bg-white rounded-3xl p-8 shadow-sm card-hover scroll-reveal">
                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center mb-4" style="background: var(--terracotta-soft);">
                            <svg class="w-6 h-6 text-[var(--terracotta-deep)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                        </div>
                        <h4 class="font-display text-lg font-bold mb-2">Assignation automatique</h4>
                        <p class="text-sm">Chaque dépôt signalé est assigné à l'agent disponible le plus proche, selon sa commune de couverture.</p>
                    </div>
                    <div class="bg-white rounded-3xl p-8 shadow-sm card-hover scroll-reveal">
                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center mb-4" style="background: var(--green-soft);">
                            <svg class="w-6 h-6 text-[var(--green-deep)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        </div>
                        <h4 class="font-display text-lg font-bold mb-2">Statistiques par commune</h4>
                        <p class="text-sm">Indicateurs de collecte, volume de déchets et performance, mis à jour en continu pour piloter les équipes.</p>
                    </div>
                    <div class="bg-white rounded-3xl p-8 shadow-sm card-hover scroll-reveal">
                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center mb-4" style="background: var(--gold-soft);">
                            <svg class="w-6 h-6 text-[var(--gold-deep)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        </div>
                        <h4 class="font-display text-lg font-bold mb-2">Notifications en temps réel</h4>
                        <p class="text-sm">Le citoyen est notifié à chaque étape : dépôt signalé, agent en route, déchets collectés.</p>
                    </div>
                    <div class="bg-white rounded-3xl p-8 shadow-sm card-hover scroll-reveal">
                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center mb-4" style="background: var(--terracotta-soft);">
                            <svg class="w-6 h-6 text-[var(--terracotta-deep)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <h4 class="font-display text-lg font-bold mb-2">Rapports exportables</h4>
                        <p class="text-sm">Générez un rapport d'activité PDF par commune en un clic, prêt pour les réunions municipales.</p>
                    </div>
                </div>
            </div>

            {{-- Sub-section: Comment ça marche --}}
            <div id="comment-ca-marche">
                <h3 class="font-display text-2xl font-extrabold mb-8">Comment ça marche</h3>
                <div class="grid md:grid-cols-3 gap-8 lg:gap-10">
                    <div class="scroll-reveal">
                        <div class="photo-frame aspect-[4/3] mb-5">
                            <span class="photo-tag">Étape 1</span>
                            <img src="https://images.unsplash.com/photo-1486312338219-ce68d2c6f44d?w=600&q=80" alt="Un citoyen photographie un dépôt sauvage dans la rue avec son téléphone">
                        </div>
                        <h4 class="font-display text-lg font-bold mb-2">Un citoyen signale un dépôt</h4>
                        <p class="text-sm">Photo, position et description du dépôt sauvage, transmises instantanément à WasteMove.</p>
                    </div>
                    <div class="scroll-reveal">
                        <div class="photo-frame aspect-[4/3] mb-5">
                            <span class="photo-tag">Étape 2</span>
                            <img src="https://images.unsplash.com/photo-1553877522-43269d4ea984?w=600&q=80" alt="Un agent municipal intervient pour collecter les déchets">
                        </div>
                        <h4 class="font-display text-lg font-bold mb-2">Un agent est envoyé</h4>
                        <p class="text-sm">L'agent de collecte disponible le plus proche reçoit une notification avec la localisation exacte du dépôt.</p>
                    </div>
                    <div class="scroll-reveal">
                        <div class="photo-frame aspect-[4/3] mb-5">
                            <span class="photo-tag">Étape 3</span>
                            <img src="https://images.unsplash.com/photo-1611258490565-4a06c019e631?w=600&q=80" alt="Quartier propre après la collecte des déchets">
                        </div>
                        <h4 class="font-display text-lg font-bold mb-2">Les déchets sont collectés</h4>
                        <p class="text-sm">Le citoyen reçoit une confirmation de collecte, et la commune conserve une donnée exploitable.</p>
                    </div>
                </div>
            </div>

        </div>
    </section>

    {{-- Final CTA --}}
    <section id="contact" class="relative py-20 lg:py-28">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="relative rounded-[2.5rem] overflow-hidden p-10 sm:p-14 text-center" style="background: linear-gradient(135deg, var(--green-soft), var(--gold-soft));">
                <div class="blob w-[260px] h-[260px] bg-[var(--green)]/15 -top-16 -left-10"></div>
                <div class="blob w-[220px] h-[220px] bg-[var(--terracotta)]/15 -bottom-10 right-0" style="animation-delay: 6s;"></div>
                <span class="eyebrow relative">Prochaine étape</span>
                <h2 class="font-display relative text-3xl sm:text-4xl lg:text-5xl font-extrabold mt-3 leading-tight">
                    La prochaine intervention<br class="hidden sm:block"> commence par un signal.
                </h2>
                <p class="relative mt-6 text-lg max-w-xl mx-auto">
                    Rejoignez les municipalités sénégalaises qui suivent déjà chaque signalement, du dépôt à la résolution, sans perdre le fil.
                </p>
                <div class="relative mt-10 flex flex-wrap justify-center gap-4">
                    <a href="{{ route('register') }}" class="btn-primary px-8 py-4 rounded-2xl">Créer un compte gratuit</a>
                    <a href="{{ route('login') }}" class="px-8 py-4 text-[var(--ink)] font-semibold rounded-2xl border border-white bg-white/70 hover:bg-white transition-all">Se connecter</a>
                </div>
            </div>
        </div>
    </section>
    </main>

    {{-- Footer --}}
    <footer class="relative bg-white/60 border-t border-[var(--line)]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid md:grid-cols-4 gap-8">
                <div class="md:col-span-2">
                    <div class="flex items-center gap-2.5 mb-4">
                        <img src="{{ asset('images/logo.png') }}" alt="WasteMove" class="h-7 w-auto">
                        <span class="font-display text-base font-extrabold text-[var(--ink)]">WasteMove</span>
                    </div>
                    <p class="text-sm max-w-md">Plateforme intelligente de gestion des déchets urbains. Faite avec teranga, pour un Dakar plus propre et plus réactif.</p>
                </div>
                <div>
                    <h4 class="font-display font-bold text-[var(--ink)] mb-4 text-sm">Navigation</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#accueil" class="hover:text-[var(--green-deep)] transition-colors">Accueil</a></li>
                        <li><a href="#a-propos" class="hover:text-[var(--green-deep)] transition-colors">À propos</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-[var(--green-deep)] transition-colors">Connexion</a></li>
                        <li><a href="{{ route('register') }}" class="hover:text-[var(--green-deep)] transition-colors">Inscription</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-display font-bold text-[var(--ink)] mb-4 text-sm">Coordonnées</h4>
                    <ul class="space-y-2 text-sm">
                        <li>contact@wastemove.sn</li>
                        <li>Dakar, Sénégal</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-[var(--line)] mt-8 pt-8 flex flex-col sm:flex-row items-center justify-between gap-3 text-sm">
                <p>&copy; {{ date('Y') }} WasteMove. Tous droits réservés.</p>
                <div class="flex gap-5">
                    <a href="#" class="hover:text-[var(--green-deep)] transition-colors">Mentions légales</a>
                    <a href="#" class="hover:text-[var(--green-deep)] transition-colors">Politique de confidentialité</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
    document.addEventListener('DOMContentLoaded', function () {

        const progressBar = document.getElementById('scroll-progress');
        function updateProgress() {
            const scrolled = window.scrollY;
            const max = document.documentElement.scrollHeight - window.innerHeight;
            progressBar.style.width = max > 0 ? (scrolled / max) * 100 + '%' : '0%';
        }
        window.addEventListener('scroll', updateProgress, { passive: true });
        updateProgress();

        const menu = document.getElementById('mobile-menu');
        const toggle = document.getElementById('menu-toggle');
        const close = document.getElementById('menu-close');
        function openMenu() { menu.classList.add('open'); toggle.setAttribute('aria-expanded', 'true'); document.body.style.overflow = 'hidden'; }
        function closeMenu() { menu.classList.remove('open'); toggle.setAttribute('aria-expanded', 'false'); document.body.style.overflow = ''; }
        toggle.addEventListener('click', openMenu);
        close.addEventListener('click', closeMenu);
        document.querySelectorAll('#mobile-menu a').forEach(a => a.addEventListener('click', closeMenu));

        const navLinks = document.querySelectorAll('[data-nav]');
        const sections = ['accueil', 'a-propos']
            .map(id => document.getElementById(id))
            .filter(Boolean);

        const spyObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    navLinks.forEach(link => {
                        link.classList.toggle('active', link.getAttribute('href') === '#' + entry.target.id);
                    });
                }
            });
        }, { rootMargin: '-40% 0px -50% 0px' });
        sections.forEach(s => spyObserver.observe(s));

        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('revealed');
                    revealObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12 });
        document.querySelectorAll('.scroll-reveal').forEach(el => revealObserver.observe(el));

        const countEls = document.querySelectorAll('[data-count]');
        const countObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (!entry.isIntersecting) return;
                const el = entry.target;
                const target = parseInt(el.getAttribute('data-count'), 10);
                const duration = 1400;
                const start = performance.now();
                function tick(now) {
                    const progress = Math.min((now - start) / duration, 1);
                    const eased = 1 - Math.pow(1 - progress, 3);
                    el.textContent = Math.round(target * eased).toLocaleString('fr-FR');
                    if (progress < 1) requestAnimationFrame(tick);
                }
                requestAnimationFrame(tick);
                countObserver.unobserve(el);
            });
        }, { threshold: 0.4 });
        countEls.forEach(el => countObserver.observe(el));
    });
    </script>

</body>
</html>
