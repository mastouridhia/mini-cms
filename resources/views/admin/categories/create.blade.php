<x-layout>
    <x-slot:title>Nouvelle catégorie - Mini-CMS</x-slot:title>

    <x-page-header title="Nouvelle catégorie" />

    <x-taxonomy-form :action="route('admin.categories.store')" :cancel="route('admin.categories.index')" />
</x-layout>
