@props(['headings' => []])
<div class="-mx-2 overflow-x-auto">
    <table class="w-full text-xs border border-gray-200 dark:border-gray-700">
        <thead>
            <tr class="border border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-800">
                @foreach($headings as $heading)
                    <th class="px-3 py-2 text-left text-[11px] font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider border border-gray-200 dark:border-gray-700 {{ $heading['class'] ?? '' }}">
                        {{ $heading['label'] }}
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
            {{ $slot }}
        </tbody>
    </table>
</div>
