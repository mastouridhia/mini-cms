@props(['action', 'item' => null, 'cancel'])

<form method="POST" action="{{ $action }}" class="max-w-xl space-y-5">
    @csrf
    @if ($item)
        @method('PUT')
    @endif

    <div>
        <label for="name" class="mb-1 block text-sm font-medium text-gray-700">Nom</label>
        <input id="name" name="name" type="text" value="{{ old('name', $item?->name) }}" required maxlength="255" class="w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
        @error('name')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="slug" class="mb-1 block text-sm font-medium text-gray-700">Identifiant URL (slug)</label>
        <input id="slug" name="slug" type="text" value="{{ old('slug', $item?->slug) }}" required maxlength="255" class="w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
        @error('slug')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex items-center gap-3">
        <button type="submit" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
            {{ $item ? 'Enregistrer les modifications' : 'Créer' }}
        </button>
        <a href="{{ $cancel }}" class="rounded-md border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Annuler</a>
    </div>
</form>
