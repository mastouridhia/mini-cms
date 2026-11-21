<x-layout>
    <x-slot:title>Service indisponible - Mini-CMS</x-slot:title>

    <x-page-header title="Service indisponible" subtitle="Erreur 503 : l'action demandée est temporairement impossible." />

    <x-alert type="error" class="mb-6 max-w-2xl">
        {{ $exception->getMessage() ?: 'Le site est momentanément indisponible. Réessayez plus tard.' }}
    </x-alert>

    <a href="{{ route('home') }}" class="inline-block rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">Retour à l'accueil</a>
</x-layout>
