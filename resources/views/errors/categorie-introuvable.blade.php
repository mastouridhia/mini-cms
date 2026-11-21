<x-layout>
    <x-slot:title>Catégorie introuvable - Mini-CMS</x-slot:title>

    <x-page-header title="Catégorie introuvable" subtitle="Erreur 404 : aucune catégorie ne correspond à cette adresse." />

    @if ($categories->isNotEmpty())
        <section aria-labelledby="categories-title">
            <h2 id="categories-title" class="text-xl font-bold mb-4">Catégories disponibles</h2>

            <ul class="flex flex-wrap gap-2">
                @foreach ($categories as $category)
                    <li><a href="{{ route('posts.category', $category) }}" class="inline-block rounded-full bg-indigo-50 px-3 py-1 text-sm font-medium text-indigo-700 hover:bg-indigo-100">{{ $category->name }}</a></li>
                @endforeach
            </ul>
        </section>
    @else
        <a href="{{ route('posts.index') }}" class="inline-block rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">Voir tous les articles</a>
    @endif
</x-layout>
