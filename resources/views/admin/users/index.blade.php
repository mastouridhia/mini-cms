<x-layout>
    <x-slot:title>Utilisateurs - Mini-CMS</x-slot:title>

    <x-page-header title="Utilisateurs" subtitle="Back-office : passer un compte de auteur à admin, et inversement." />

    <div class="overflow-x-auto rounded-lg border border-gray-200 bg-white">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-50 text-gray-700">
                <tr>
                    <th scope="col" class="px-4 py-3">Nom</th>
                    <th scope="col" class="px-4 py-3">Adresse e-mail</th>
                    <th scope="col" class="px-4 py-3">Rôle</th>
                    <th scope="col" class="px-4 py-3">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach ($users as $user)
                    <tr>
                        <td class="px-4 py-3 font-medium">{{ $user->name }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $user->email }}</td>
                        <td class="px-4 py-3">{{ $user->role }}</td>
                        <td class="px-4 py-3">
                            <form method="POST" action="{{ route('admin.users.role', $user) }}">
                                @csrf
                                @method('PATCH')

                                <input type="hidden" name="role" value="{{ $user->isAdmin() ? 'author' : 'admin' }}">
                                <button type="submit" class="text-indigo-600 hover:underline">
                                    {{ $user->isAdmin() ? 'Passer auteur' : 'Passer admin' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-layout>
