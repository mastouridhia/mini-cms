<x-layout>
    <x-slot:title>Modifier la catégorie - Mini-CMS</x-slot:title>

    <x-page-header :title="'Modifier la catégorie : '.$category->name" />

    <x-taxonomy-form :action="route('admin.categories.update', $category)" :item="$category" :cancel="route('admin.categories.index')" />
</x-layout>
