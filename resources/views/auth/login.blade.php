<x-layout>
    <x-slot:title>Connexion - Mini-CMS</x-slot:title>

    <x-page-header title="Connexion" subtitle="Connectez-vous pour écrire et gérer vos articles." />

    <form method="POST" action="{{ route('login.store') }}" class="max-w-md space-y-5">
        @csrf

        <div>
            <label for="email" class="mb-1 block text-sm font-medium text-gray-700">Adresse e-mail</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="email" class="w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
            @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="mb-1 block text-sm font-medium text-gray-700">Mot de passe</label>
            <input id="password" name="password" type="password" required autocomplete="current-password" class="w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
            @error('password')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">Se connecter</button>

        <p class="text-sm text-gray-600">Pas encore de compte ? <a href="{{ route('register') }}" class="text-indigo-600 hover:underline">Créer un compte</a></p>
    </form>
</x-layout>
