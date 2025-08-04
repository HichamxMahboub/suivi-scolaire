@props(['unreadCount' => 0])

<div class="relative inline-block" x-data="messageNotifier" x-init="init()">
    <a href="{{ route('messages.index') }}" class="relative flex items-center text-gray-600 hover:text-blue-600 transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 3.26a2 2 0 001.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
        </svg>
        
        <span x-show="unreadCount > 0" 
              x-text="unreadCount" 
              class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center min-w-[20px]">
        </span>
    </a>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('messageNotifier', () => ({
        unreadCount: {{ $unreadCount }},
        
        init() {
            this.updateUnreadCount();
            // Mettre à jour toutes les 30 secondes
            setInterval(() => {
                this.updateUnreadCount();
            }, 30000);
        },
        
        async updateUnreadCount() {
            try {
                const response = await fetch('/messages/unread-count');
                const data = await response.json();
                this.unreadCount = data.count;
            } catch (error) {
                console.error('Erreur lors de la récupération du nombre de messages non lus:', error);
            }
        }
    }))
})
</script>
