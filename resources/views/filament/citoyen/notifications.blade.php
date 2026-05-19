<x-filament-panels::page>
    <div class="space-y-3">
        @forelse($this->getNotifications() as $notification)
        <div class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 flex items-start justify-between gap-4 {{ $notification->read_at ? '' : 'border-l-4 border-l-primary-500' }}">
            <div class="flex-1">
                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $notification->data['title'] ?? 'Notification' }}</p>
                @if(isset($notification->data['body']))
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $notification->data['body'] }}</p>
                @endif
                <p class="text-xs text-gray-500 dark:text-gray-500 mt-2">{{ $notification->created_at->diffForHumans() }}</p>
            </div>
            @if(!$notification->read_at)
            <button wire:click="marquerLue('{{ $notification->id }}')" class="text-xs text-primary-600 dark:text-primary-400 hover:underline shrink-0">
                Marquer lue
            </button>
            @endif
        </div>
        @empty
        <div class="p-8 text-center text-gray-500 dark:text-gray-400">
            <svg class="w-12 h-12 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
            <p>Aucune notification</p>
        </div>
        @endforelse

        <div class="mt-4">
            {{ $this->getNotifications()->links() }}
        </div>
    </div>
</x-filament-panels::page>
