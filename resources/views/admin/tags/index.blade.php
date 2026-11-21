<x-layout>
    <x-slot:title>Tags - Mini-CMS</x-slot:title>

    <x-page-header title="Tags" subtitle="Back-office : créer, renommer et supprimer les tags." />

    <a href="{{ route('admin.tags.create') }}" class="inline-block mb-6 rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">Nouveau tag</a>

    @if ($tags->isEmpty())
        <x-alert type="warning" title="Aucun tag">Créez le premier tag.</x-alert>
    @else
        <div class="overflow-x-auto rounded-lg border border-gray-200 bg-white">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50 text-gray-700">
                    <tr>
                        <th scope="col" class="px-4 py-3">Nom</th>
                        <th scope="col" class="px-4 py-3">Slug</th>
                        <th scope="col" class="px-4 py-3">Articles</th>
                        <th scope="col" class="px-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($tags as $tag)
                        <tr class="align-top">
                            <td class="px-4 py-3 font-medium">{{ $tag->name }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $tag->slug }}</td>
                            <td class="px-4 py-3">{{ $tag->posts_count }}</td>
                            <td class="px-4 py-3">
                                <div class="flex flex-wrap items-start gap-3">
                                    <a href="{{ route('admin.tags.edit', $tag) }}" class="text-indigo-600 hover:underline">Modifier</a>

                                    <details>
                                        <summary class="cursor-pointer text-red-700 hover:underline">Supprimer</summary>

                                        <form method="POST" action="{{ route('admin.tags.destroy', $tag) }}" class="mt-2 rounded-md border border-red-200 bg-red-50 p-3">
                                            @csrf
                                            @method('DELETE')

                                            <p class="mb-2 text-xs text-red-900">Le tag sera retiré de ses articles, les articles restent.</p>
                                            <button type="submit" class="rounded-md bg-red-600 px-3 py-1 text-xs font-medium text-white hover:bg-red-700">Confirmer la suppression</button>
                                        </form>
                                    </details>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</x-layout>
