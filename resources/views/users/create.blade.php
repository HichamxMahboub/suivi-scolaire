@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 mb-2">Créer un nouvel utilisateur</h1>
                        <p class="text-gray-600">Ajouter un nouvel utilisateur au système</p>
                    </div>
                    <a href="{{ route('users.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Retour à la liste
                    </a>
                </div>

                <form method="POST" action="{{ route('users.store') }}" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nom complet</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Rôle</label>
                            <select name="role" id="role" required
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Sélectionner un rôle</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>👨‍💼 Administrateur</option>
                                <option value="encadrant" {{ old('role') == 'encadrant' ? 'selected' : '' }}>🎓 Encadrant pédagogique</option>
                                <option value="medical" {{ old('role') == 'medical' ? 'selected' : '' }}>🏥 Personnel médical</option>
                                <option value="teacher" {{ old('role') == 'teacher' ? 'selected' : '' }}>📚 Enseignant</option>
                                <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>👤 Utilisateur</option>
                                <option value="student" {{ old('role') == 'student' ? 'selected' : '' }}>🎒 Élève</option>
                            </select>
                            @error('role')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            
                            <!-- Role descriptions -->
                            <div class="mt-3 text-xs text-gray-600 space-y-1">
                                <div id="admin-desc" class="role-desc" style="display:none;">
                                    <strong>Administrateur :</strong> Accès complet au système, gestion des utilisateurs, toutes les permissions
                                </div>
                                <div id="encadrant-desc" class="role-desc" style="display:none;">
                                    <strong>Encadrant :</strong> Gestion des élèves et notes, accès limité aux informations médicales
                                </div>
                                <div id="medical-desc" class="role-desc" style="display:none;">
                                    <strong>Personnel médical :</strong> Accès exclusif aux informations médicales des élèves
                                </div>
                                <div id="teacher-desc" class="role-desc" style="display:none;">
                                    <strong>Enseignant :</strong> Consultation des profils élèves, gestion des notes
                                </div>
                                <div id="user-desc" class="role-desc" style="display:none;">
                                    <strong>Utilisateur :</strong> Accès de base au système
                                </div>
                                <div id="student-desc" class="role-desc" style="display:none;">
                                    <strong>Élève :</strong> Accès limité à son propre profil
                                </div>
                            </div>
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Mot de passe</label>
                            <input type="password" name="password" id="password" required
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('password')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirmer le mot de passe</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" required
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>

                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('users.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Annuler
                        </a>
                        <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Créer l'utilisateur
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const roleSelect = document.getElementById('role');
    const roleDescs = document.querySelectorAll('.role-desc');
    
    roleSelect.addEventListener('change', function() {
        // Hide all descriptions
        roleDescs.forEach(desc => desc.style.display = 'none');
        
        // Show selected role description
        if (this.value) {
            const selectedDesc = document.getElementById(this.value + '-desc');
            if (selectedDesc) {
                selectedDesc.style.display = 'block';
            }
        }
    });
    
    // Show description for initially selected role
    if (roleSelect.value) {
        const selectedDesc = document.getElementById(roleSelect.value + '-desc');
        if (selectedDesc) {
            selectedDesc.style.display = 'block';
        }
    }
});
</script>
@endsection 