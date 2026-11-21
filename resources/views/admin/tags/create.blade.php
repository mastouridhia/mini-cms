<x-layout>
    <x-slot:title>Nouveau tag - Mini-CMS</x-slot:title>

    <x-page-header title="Nouveau tag" />

    <x-taxonomy-form :action="route('admin.tags.store')" :cancel="route('admin.tags.index')" />
</x-layout>
