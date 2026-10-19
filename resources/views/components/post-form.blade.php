@props(['post' => null])

<form method="POST" action="{{ $post ? route('posts.update', $post) : route('posts.store') }}" class="max-w-2xl space-y-5">
    @csrf
    @if ($post)
        @method('PUT')
    @endif

    <div>
        <label for="title" class="mb-1 block text-sm font-medium text-gray-700">Titre</label>
        <input id="title" name="title" type="text" value="{{ old('title', $post?->title) }}" required maxlength="255" class="w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
        @error('title')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="slug" class="mb-1 block text-sm font-medium text-gray-700">Identifiant URL (slug)</label>
        <input id="slug" name="slug" type="text" value="{{ old('slug', $post?->slug) }}" required maxlength="255" class="w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
        @error('slug')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="excerpt" class="mb-1 block text-sm font-medium text-gray-700">Extrait</label>
        <textarea id="excerpt" name="excerpt" rows="2" maxlength="255" class="w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">{{ old('excerpt', $post?->excerpt) }}</textarea>
        @error('excerpt')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="body" class="mb-1 block text-sm font-medium text-gray-700">Contenu</label>
        <textarea id="body" name="body" rows="8" required class="w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">{{ old('body', $post?->body) }}</textarea>
        @error('body')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <label for="is_published" class="flex items-center gap-2 text-sm font-medium text-gray-700">
        <input id="is_published" name="is_published" type="checkbox" value="1" @checked(old('is_published', $post?->is_published ?? false)) class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
        Publier cet article
    </label>

    <div class="flex items-center gap-3">
        <button type="submit" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
            {{ $post ? 'Enregistrer les modifications' : 'Créer l’article' }}
        </button>
        <a href="{{ $post ? route('posts.show', $post) : route('posts.index') }}" class="rounded-md border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
            Annuler
        </a>
    </div>
</form>
