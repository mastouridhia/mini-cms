<x-layout>
    <x-slot:title>Catégories - Mini-CMS</x-slot:title>

    <x-page-header title="Catégories" subtitle="Back-office : créer, renommer et supprimer les catégories." />

    <a href="{{ route('admin.categories.create') }}" class="inline-block mb-6 rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">Nouvelle catégorie</a>

    @if ($categories->isEmpty())
        <x-alert type="warning" title="Aucune catégorie">Créez la première catégorie.</x-alert>
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
                    @foreach ($categories as $category)
                        <tr class="align-top">
                            <td class="px-4 py-3 font-medium">{{ $category->name }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $category->slug }}</td>
                            <td class="px-4 py-3">{{ $category->posts_count }}</td>
                            <td class="px-4 py-3">
                                <div class="flex flex-wrap items-start gap-3">
                                    <a href="{{ route('admin.categories.edit', $category) }}" class="text-indigo-600 hover:underline">Modifier</a>

                                    <details>
                                        <summary class="cursor-pointer text-red-700 hover:underline">Supprimer</summary>

                                        <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" class="mt-2 rounded-md border border-red-200 bg-red-50 p-3">
                                            @csrf
                                            @method('DELETE')

                                            <p class="mb-2 text-xs text-red-900">Ses articles resteront sans catégorie.</p>
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
