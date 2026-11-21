<x-layout>
    <x-slot:title>Article introuvable - Mini-CMS</x-slot:title>

    <x-page-header title="Article introuvable" subtitle="Erreur 404 : cet article n'existe pas ou a été supprimé." />

    <x-alert type="warning" class="mb-6 max-w-2xl">
        Vérifiez l'adresse, ou revenez à la liste des articles.
    </x-alert>

    <a href="{{ route('posts.index') }}" class="inline-block rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">Voir tous les articles</a>

    @if ($recentPosts->isNotEmpty())
        <section class="mt-10" aria-labelledby="recent-title">
            <h2 id="recent-title" class="text-xl font-bold mb-4">Articles récents</h2>

            <ul class="space-y-2">
                @foreach ($recentPosts as $recentPost)
                    <li><a href="{{ route('posts.show', $recentPost) }}" class="text-indigo-600 hover:underline">{{ $recentPost->title }}</a></li>
                @endforeach
            </ul>
        </section>
    @endif
</x-layout>
