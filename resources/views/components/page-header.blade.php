@props(['title', 'subtitle' => null])

<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900">{{ $title }}</h1>
    @if ($subtitle)
        <p class="mt-2 text-sm text-gray-600">{{ $subtitle }}</p>
    @endif
</div>