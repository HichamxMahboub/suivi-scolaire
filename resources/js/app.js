import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

document.addEventListener('DOMContentLoaded', function() {
    // Bouton Sauvegarder
    const saveBtn = document.getElementById('save-settings');
    const toast = document.getElementById('settings-toast');
    if(saveBtn && toast) {
        saveBtn.onclick = function() {
            // Simule la sauvegarde (localStorage déjà mis à jour par les autres handlers)
            toast.classList.remove('hidden');
            setTimeout(() => { toast.classList.add('hidden'); }, 2000);
        };
    }
});
