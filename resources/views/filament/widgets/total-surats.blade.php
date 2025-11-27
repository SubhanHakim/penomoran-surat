<div class="p-4 bg-white rounded-lg shadow-sm border">
    <div class="text-sm text-gray-500">Total Surat</div>
    <div class="mt-2 text-2xl font-semibold text-gray-900">{{ $total }}</div>

    <div class="mt-4 grid grid-cols-2 gap-4">
        <div class="p-3 bg-gray-50 rounded">
            <div class="text-xs text-gray-500">Masuk</div>
            <div class="mt-1 text-lg font-medium text-gray-800">{{ $masuk }}</div>
        </div>
        <div class="p-3 bg-gray-50 rounded">
            <div class="text-xs text-gray-500">Keluar</div>
            <div class="mt-1 text-lg font-medium text-gray-800">{{ $keluar }}</div>
        </div>
    </div>
</div>