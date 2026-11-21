@php
    $reason = $exception->getMessage();
    $reason = $reason === '' || $reason === 'This action is unauthorized.'
        ? "Vous n'avez pas le droit d'effectuer cette action."
        : $reason;
@endphp

<x-layout>
    <x-slot:title>Accès refusé - Mini-CMS</x-slot:title>

    <x-page-header title="Accès refusé" subtitle="Erreur 403 : cette page n'est pas accessible avec votre compte." />

    <x-alert type="error" class="mb-6 max-w-2xl">{{ $reason }}</x-alert>

    <a href="{{ route('home') }}" class="inline-block rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">Retour à l'accueil</a>
</x-layout>
