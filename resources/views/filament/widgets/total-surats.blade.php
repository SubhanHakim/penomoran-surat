<!-- filepath: resources/views/filament/widgets/total-surats.blade.php -->
<x-filament::card>
    <div class="flex items-center justify-between">
        <div>
            <div class="text-sm font-medium text-gray-500">Total Surat</div>
            <div class="mt-2 text-2xl font-semibold text-gray-900">{{ $total }}</div>
        </div>
        <div class="text-xs text-gray-400">Updated: {{ now()->format('d M Y') }}</div>
    </div>

    <div class="mt-4 grid grid-cols-2 gap-4">
        <div class="flex items-center justify-between rounded-lg bg-gray-50 p-3">
            <div>
                <div class="text-xs text-gray-500">Masuk</div>
                <div class="mt-1 text-lg font-semibold text-gray-800">{{ $masuk }}</div>
            </div>
            <div class="flex items-center justify-center h-10 w-10 rounded-full bg-white shadow">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M2.94 6.94a1.5 1.5 0 012.12 0L10 11.88l4.94-4.94a1.5 1.5 0 112.12 2.12l-6 6a1.5 1.5 0 01-2.12 0l-6-6a1.5 1.5 0 010-2.12z" />
                </svg>
            </div>
        </div>

        <div class="flex items-center justify-between rounded-lg bg-gray-50 p-3">
            <div>
                <div class="text-xs text-gray-500">Keluar</div>
                <div class="mt-1 text-lg font-semibold text-gray-800">{{ $keluar }}</div>
            </div>
            <div class="flex items-center justify-center h-10 w-10 rounded-full bg-white shadow">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M10 3a1 1 0 00-1 1v8H6l4 4 4-4h-3V4a1 1 0 00-1-1z" />
                </svg>
            </div>
        </div>
    </div>
</x-filament::card>