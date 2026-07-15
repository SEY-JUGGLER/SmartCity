<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion — WasteMove</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-white">

<div class="min-h-screen flex">
    {{-- Left Side - Form --}}
    <div class="flex-1 flex items-center justify-center px-6 lg:px-12 py-12">
        <div class="w-full max-w-md">
            {{-- Logo --}}
            <div class="mb-10">
                <a href="/" class="flex items-center gap-2.5">
                    <img src="{{ asset('images/logo.png') }}" alt="WasteMove" class="h-9 w-auto">
                    <span class="text-xl font-bold bg-gradient-to-r from-emerald-600 to-teal-500 bg-clip-text text-transparent">WasteMove</span>
                </a>
            </div>

            <h1 class="text-2xl font-bold text-gray-900 mb-1">Content de vous revoir</h1>
            <p class="text-gray-500 mb-8">Connectez-vous à votre espace WasteMove</p>

            @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-2xl text-sm flex items-center gap-3">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>{{ $errors->first() }}</span>
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Adresse email</label>
                    <div class="relative">
                        <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus
                            placeholder="vous@exemple.com"
                            class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:bg-white transition-all">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Mot de passe</label>
                    <div class="relative">
                        <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        <input type="password" name="password" required
                            placeholder="••••••••"
                            class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:bg-white transition-all">
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2.5 cursor-pointer">
                        <input type="checkbox" name="remember"
                            class="w-4 h-4 rounded-lg border-gray-300 text-emerald-600 focus:ring-emerald-500">
                        <span class="text-sm text-gray-600">Se souvenir de moi</span>
                    </label>
                    @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-sm font-medium text-emerald-600 hover:text-emerald-700 transition-colors">
                        Mot de passe oublié ?
                    </a>
                    @endif
                </div>

                <button type="submit"
                    class="w-full py-3 bg-gradient-to-r from-emerald-500 to-teal-500 text-white font-semibold rounded-2xl hover:shadow-lg hover:shadow-emerald-200 hover:scale-[1.02] active:scale-[0.98] transition-all">
                    Se connecter
                </button>
            </form>

            <p class="text-center text-sm text-gray-500 mt-8">
                Pas encore de compte ?
                <a href="{{ route('register') }}" class="font-semibold text-emerald-600 hover:text-emerald-700 transition-colors">Créer un compte</a>
            </p>
        </div>
    </div>

    {{-- Right Side - Visual --}}
    <div class="hidden lg:flex flex-1 bg-gradient-to-br from-emerald-600 via-emerald-700 to-teal-700 relative items-center justify-center overflow-hidden">
        <div class="absolute inset-0">
            <div class="absolute top-20 left-20 w-96 h-96 bg-emerald-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
            <div class="absolute bottom-20 right-20 w-96 h-96 bg-teal-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-64 h-64 bg-cyan-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-4000"></div>
        </div>
        <div class="relative text-center px-12">
            <div class="w-24 h-24 mx-auto bg-white/15 backdrop-blur-xl rounded-3xl flex items-center justify-center mb-8">
                <img src="{{ asset('images/logo.png') }}" alt="WasteMove" class="h-14 w-auto brightness-0 invert">
            </div>
<h2 class="text-3xl font-bold text-white mb-4">Bienvenue sur WasteMove</h2>
                            <p class="text-emerald-100 text-lg max-w-md mx-auto">La plateforme de gestion des déchets qui connecte citoyens et services municipaux pour un Dakar plus propre.</p>
            <div class="mt-12 flex justify-center gap-8">
                <div class="text-center">
                    <p class="text-3xl font-bold text-white">{{ number_format($signalements, 0, ',', ' ') }}+</p>
                    <p class="text-sm text-emerald-200 mt-1">Signalements</p>
                </div>
                <div class="w-px bg-emerald-500/30"></div>
                <div class="text-center">
                    <p class="text-3xl font-bold text-white">{{ $agents }}+</p>
                    <p class="text-sm text-emerald-200 mt-1">Agents</p>
                </div>
                <div class="w-px bg-emerald-500/30"></div>
                <div class="text-center">
                    <p class="text-3xl font-bold text-white">{{ $satisfaction }}%</p>
                    <p class="text-sm text-emerald-200 mt-1">Satisfaction</p>
                </div>
            </div>
            <div class="mt-16 grid grid-cols-2 gap-4 max-w-sm mx-auto">
                <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 text-left">
                    <svg class="w-6 h-6 text-emerald-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="text-white text-sm font-medium">Suivi temps réel</p>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 text-left">
                    <svg class="w-6 h-6 text-emerald-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    <p class="text-white text-sm font-medium">Intervention rapide</p>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 text-left">
                    <svg class="w-6 h-6 text-emerald-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    <p class="text-white text-sm font-medium">Statistiques</p>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 text-left">
                    <svg class="w-6 h-6 text-emerald-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    <p class="text-white text-sm font-medium">Sécurisé</p>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
