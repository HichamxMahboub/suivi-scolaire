<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Delete Account') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
        {{ __('Supprimer le compte') }}
    </button>

    <!-- Modal de confirmation de suppression -->
    <div id="confirm-user-deletion-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
            <h2 class="text-xl font-bold mb-4 text-red-700">Confirmer la suppression du compte</h2>
            <p class="mb-6 text-gray-700">Êtes-vous sûr de vouloir supprimer votre compte ? Cette action est irréversible.</p>
            <form method="POST" action="{{ route('profile.destroy') }}">
                @csrf
                @method('DELETE')
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">Annuler</button>
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Supprimer le compte</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        function openModal() {
            document.getElementById('confirm-user-deletion-modal').classList.remove('hidden');
        }
        function closeModal() {
            document.getElementById('confirm-user-deletion-modal').classList.add('hidden');
        }
        // Remplacer l'ancien dispatch par openModal()
        document.querySelectorAll('[data-modal-toggle="confirm-user-deletion"]').forEach(btn => {
            btn.addEventListener('click', openModal);
        });
    </script>
</section> 