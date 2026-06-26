@php
    $links = [
        ['route' => 'agent.dashboard', 'label' => 'Accueil'],
        ['route' => 'agent.missions.index', 'label' => 'Missions'],
        ['route' => 'agent.historique', 'label' => 'Historique'],
        ['route' => 'agent.pointage', 'label' => 'Pointage'],
        ['route' => 'agent.support', 'label' => 'Support'],
        ['route' => 'agent.materiels', 'label' => 'Matériels'],
        ['route' => 'agent.carte', 'label' => 'Carte'],
        ['route' => 'agent.classement', 'label' => 'Classement'],
        ['route' => 'agent.notifications', 'label' => 'Notifications'],
        ['route' => 'agent.profil', 'label' => 'Profil'],
    ];
@endphp
<nav class="sticky top-0 z-30 bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl border-b border-slate-200 dark:border-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 gap-4">
            <a href="{{ route('agent.dashboard') }}" class="flex items-center gap-2 shrink-0">
                <img src="{{ asset('images/logo.png') }}" alt="SmartCity" class="h-8 w-auto">
                <span class="text-lg font-bold text-slate-900 dark:text-white hidden sm:inline">SmartCity Agent</span>
            </a>
            <div class="flex items-center gap-1 overflow-x-auto text-xs sm:text-sm">
                @foreach ($links as $link)
                    <a href="{{ route($link['route']) }}"
                       class="px-2.5 py-1.5 rounded-lg whitespace-nowrap transition-colors {{ request()->routeIs($link['route']) || (str_contains($link['route'], 'missions') && request()->routeIs('agent.missions.*')) ? 'bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-300' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-gray-800' }}">
                        {{ $link['label'] }}
                    </a>
                @endforeach
            </div>
            <form method="POST" action="{{ route('logout') }}" class="shrink-0">
                @csrf
                <button type="submit" class="text-sm text-slate-600 hover:text-red-600 dark:text-slate-400">Déconnexion</button>
            </form>
        </div>
    </div>
</nav>
