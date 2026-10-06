<form method="GET" class="flex gap-4 items-end mb-6 bg-white shadow rounded-lg p-4">
    <div>
        <label class="block text-sm font-medium text-gray-700">Desde</label>
        <input type="date" name="desde" value="{{ request('desde', $desde->format('Y-m-d')) }}" class="mt-1 block rounded border-gray-300">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Hasta</label>
        <input type="date" name="hasta" value="{{ request('hasta', $hasta->format('Y-m-d')) }}" class="mt-1 block rounded border-gray-300">
    </div>
    <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded hover:bg-gray-700">Filtrar</button>
    <a href="{{ $pdfRoute }}?desde={{ request('desde', $desde->format('Y-m-d')) }}&hasta={{ request('hasta', $hasta->format('Y-m-d')) }}"
       class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700" target="_blank">
        Exportar PDF
    </a>
</form>