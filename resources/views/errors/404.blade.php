<x-layout>
    <x-slot:title>Page introuvable - Mini-CMS</x-slot:title>

    <x-page-header title="Page introuvable" subtitle="Erreur 404 : l'adresse demandée n'existe pas." />

    <x-alert type="warning" class="mb-6 max-w-2xl">
        Le lien est peut-être ancien, ou l'adresse contient une faute de frappe.
    </x-alert>

    <a href="{{ route('home') }}" class="inline-block rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">Retour à l'accueil</a>
</x-layout>
