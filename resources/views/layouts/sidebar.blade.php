<style>
    #sidebar {
        transition: transform 0.3s cubic-bezier(.4,0,.2,1);
        background: #FFD600;
        border-top-left-radius: 1.5rem;
        border-bottom-left-radius: 1.5rem;
        box-shadow: none;
    }
    #sidebar.closed {
        transform: translateX(100%);
    }
    #sidebar-logo-flottant {
        position: fixed;
        top: 1.5rem;
        right: 1.5rem;
        z-index: 50;
        background: white;
        border-radius: 9999px;
        border: 2.5px solid #FFD600;
        width: 56px;
        height: 56px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    }
    .sidebar-active {
        background: #FFB300;
        border-radius: 1rem;
        color: #222 !important;
        font-weight: 600;
    }
</style>
<!-- Logo flottant TOUJOURS visible -->
<div id="sidebar-logo-flottant" onclick="toggleSidebar()">
    <img src="{{ asset('logo-ecole.png') }}" alt="Logo" class="h-10 w-auto">
</div>
<aside id="sidebar" class="fixed top-0 right-0 h-full w-[220px] z-40 flex flex-col transition-transform duration-300 rounded-l-2xl closed">
    <div id="sidebar-content" style="margin-top: 5rem;">
        <!-- Navigation -->
        <nav class="flex-1 px-0 py-2 space-y-1">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-6 py-3 text-gray-900 font-medium text-base transition rounded-xl @if(request()->routeIs('dashboard')) sidebar-active @endif">
                <span class="w-8 text-center text-xl"><i class="fas fa-home"></i></span> <span>Tableau de bord</span>
            </a>
            <a href="{{ route('eleves.index') }}" class="flex items-center gap-3 px-6 py-3 text-gray-900 font-medium text-base transition rounded-xl @if(request()->routeIs('eleves.*')) sidebar-active @endif">
                <span class="w-8 text-center text-xl"><i class="fas fa-users"></i></span> <span>Élèves</span>
            </a>
            <a href="{{ route('classes.index') }}" class="flex items-center gap-3 px-6 py-3 text-gray-900 font-medium text-base transition rounded-xl @if(request()->routeIs('classes.*')) sidebar-active @endif">
                <span class="w-8 text-center text-xl"><i class="fas fa-book"></i></span> <span>Classes</span>
            </a>
            <a href="{{ route('encadrants.index') }}" class="flex items-center gap-3 px-6 py-3 text-gray-900 font-medium text-base transition rounded-xl @if(request()->routeIs('encadrants.*')) sidebar-active @endif">
                <span class="w-8 text-center text-xl"><i class="fas fa-chalkboard-teacher"></i></span> <span>Encadrants</span>
            </a>
            <a href="{{ route('messages.index') }}" class="flex items-center gap-3 px-6 py-3 text-gray-900 font-medium text-base transition rounded-xl @if(request()->routeIs('messages.*')) sidebar-active @endif">
                <span class="w-8 text-center text-xl"><i class="fas fa-envelope"></i></span> <span>Messages</span>
            </a>
            <a href="{{ route('settings') }}" class="flex items-center gap-3 px-6 py-3 text-gray-900 font-medium text-base transition rounded-xl @if(request()->routeIs('settings')) sidebar-active @endif">
                <span class="w-8 text-center text-xl"><i class="fas fa-cog"></i></span> <span>Paramètres</span>
            </a>
        </nav>
        <!-- Profil utilisateur simple en bas -->
        <div class="mt-auto flex flex-col items-center py-6">
            <div class="h-12 w-12 rounded-full bg-white flex items-center justify-center text-[#FFD600] font-bold text-xl mb-2">
                {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
            </div>
            <div class="text-gray-900 text-center">
                <div class="font-semibold text-base">{{ Auth::user()->name }}</div>
                <div class="text-xs opacity-80">{{ Auth::user()->email }}</div>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="mt-2">
                @csrf
                <button type="submit" class="text-gray-900 text-xs font-bold hover:underline">Déconnexion</button>
            </form>
        </div>
    </div>
</aside>
<!-- FontAwesome CDN pour les icônes -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const content = document.getElementById('sidebar-content');
        if (sidebar.classList.contains('closed')) {
            sidebar.classList.remove('closed');
            content.style.display = '';
        } else {
            sidebar.classList.add('closed');
            content.style.display = 'none';
        }
    }
    // Afficher/masquer le menu selon l'état de la sidebar
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('sidebar');
        const content = document.getElementById('sidebar-content');
        if (sidebar.classList.contains('closed')) {
            content.style.display = 'none';
        }
    });
</script> 