<x-layout>
    <x-slot:title>Modifier le tag - Mini-CMS</x-slot:title>

    <x-page-header :title="'Modifier le tag : '.$tag->name" />

    <x-taxonomy-form :action="route('admin.tags.update', $tag)" :item="$tag" :cancel="route('admin.tags.index')" />
</x-layout>
