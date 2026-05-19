<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 dark:from-gray-950 dark:to-gray-900">
    @include('livewire.partials.citoyen-nav')
    <main class="max-w-2xl mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-6 text-slate-900 dark:text-white">Mon profil</h1>
        <form wire:submit="save" class="space-y-4 bg-white dark:bg-gray-900 rounded-2xl p-6 border border-slate-200 dark:border-gray-800">
            <div class="grid grid-cols-2 gap-4">
                <div><label class="text-sm">Prénom</label><input wire:model="prenom" class="w-full mt-1 rounded-xl border-slate-300 dark:border-gray-600 dark:bg-gray-800"></div>
                <div><label class="text-sm">Nom</label><input wire:model="name" class="w-full mt-1 rounded-xl border-slate-300 dark:border-gray-600 dark:bg-gray-800"></div>
                <div class="col-span-2"><label class="text-sm">Email</label><input wire:model="email" type="email" class="w-full mt-1 rounded-xl border-slate-300 dark:border-gray-600 dark:bg-gray-800"></div>
                <div><label class="text-sm">Téléphone</label><input wire:model="telephone" class="w-full mt-1 rounded-xl border-slate-300 dark:border-gray-600 dark:bg-gray-800"></div>
                <div><label class="text-sm">Âge</label><input wire:model="age" type="number" class="w-full mt-1 rounded-xl border-slate-300 dark:border-gray-600 dark:bg-gray-800"></div>
            </div>
            <input type="password" wire:model="current_password" placeholder="Mot de passe actuel" class="w-full rounded-xl border-slate-300 dark:border-gray-600 dark:bg-gray-800">
            <input type="password" wire:model="new_password" placeholder="Nouveau mot de passe" class="w-full rounded-xl border-slate-300 dark:border-gray-600 dark:bg-gray-800">
            <label class="flex items-center gap-2 text-sm"><input type="checkbox" wire:model="notification_systeme"> Notifications système</label>
            <button type="submit" class="px-6 py-2 rounded-xl bg-emerald-500 text-white">Enregistrer</button>
        </form>
    </main>
</div>
