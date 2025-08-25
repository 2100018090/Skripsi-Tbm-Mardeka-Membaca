<!DOCTYPE html>
<html lang="en">

<body class="bg-white">
    <!-- Dark Mode Navbar -->
    <nav class="sticky top-0 z-50 bg-[#64C0B7] text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="flex justify-between items-center h-16">
            
            <!-- Logo Kiri -->
            <div class="flex-shrink-0">
              <a href="#">
                <img class="h-8 w-auto" src="{{ asset('storage/icon/logo2.svg') }}" alt="Logo">
              </a>
            </div>
      
            <!-- Navigasi Tengah -->
            <div class="hidden md:flex flex-1 justify-center">
              <div class="flex space-x-8">
                <a href="#beranda" class="text-black font-bold border-b-2 border-black px-1 pt-1 inline-flex items-center text-lg">Beranda</a>
                <a href="#tentang" class="text-black hover:text-white border-b-2 border-transparent hover:border-white px-1 pt-1 inline-flex items-center text-lg font-bold">Tentang</a>
                <a href="#buku populer" class="text-black hover:text-white border-b-2 border-transparent hover:border-white px-1 pt-1 inline-flex items-center text-lg font-bold">Buku Populer</a>
                <a href="#pengurus" class="text-black hover:text-white border-b-2 border-transparent hover:border-white px-1 pt-1 inline-flex items-center text-lg font-bold">Pengurus</a>
              </div>
            </div>
      
            <!-- Profil Kanan -->
            <div class="flex items-center space-x-4">
              <!-- Tombol profil desktop -->
              <div class="relative hidden md:block">
                <button type="button" class="bg-gray-800 flex text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white" id="user-menu-button">
                  <span class="sr-only">Open user menu</span>
                  <img class="h-8 w-8 rounded-full" src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="">
                </button>
                <!-- Dropdown (optional JS toggle) -->
                <div class="hidden absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 py-1" id="user-menu">
                  <a href="{{ route('profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profil Anggota</a>
                  <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Keluar</a>
                </div>
              </div>
      
              <!-- Tombol menu mobile -->
              <div class="md:hidden">
                <button id="mobile-menu-button" class="text-white hover:text-gray-200 focus:outline-none">
                  <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                  </svg>
                </button>
              </div>
            </div>
          </div>
        </div>
      
        <!-- Menu Mobile -->
        <div class="md:hidden hidden" id="mobile-menu">
          <div class="px-2 pt-2 pb-3 space-y-1">
            <a href="#" class="block px-3 py-2 rounded-md text-base font-medium text-black hover:text-white hover:bg-gray-700">Beranda</a>
            <a href="#" class="block px-3 py-2 rounded-md text-base font-medium text-black hover:text-white hover:bg-gray-700">Tentang</a>
            <a href="#" class="block px-3 py-2 rounded-md text-base font-medium text-black hover:text-white hover:bg-gray-700">Buku Populer</a>
            <a href="#" class="block px-3 py-2 rounded-md text-base font-medium text-black hover:text-white hover:bg-gray-700">Forum</a>
          </div>
          <div class="pt-4 pb-3 border-t border-gray-700">
            <div class="flex items-center px-5">
              <div class="flex-shrink-0">
                <img class="h-10 w-10 rounded-full" src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="">
              </div>
              <div class="ml-3">
                <div class="text-base font-medium text-black">Tom Cook</div>
                <div class="text-sm font-medium text-black">tom@example.com</div>
              </div>
            </div>
            <div class="mt-3 px-2 space-y-1">
              <a href="{{ route('profile') }}" class="block px-3 py-2 rounded-md text-base font-medium text-black hover:text-white hover:bg-gray-700">Profil Anggota</a>
              <a href="#" class="block px-3 py-2 rounded-md text-base font-medium text-black hover:text-white hover:bg-gray-700">Keluar</a>
            </div>
          </div>
        </div>
      </nav>

    
    
    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            const mobileMenu = document.getElementById('mobile-menu');
            const isExpanded = this.getAttribute('aria-expanded') === 'true';
            this.setAttribute('aria-expanded', !isExpanded);
            mobileMenu.classList.toggle('hidden');
        });
        
        // User menu toggle
        document.getElementById('user-menu-button')?.addEventListener('click', function() {
            const userMenu = document.getElementById('user-menu');
            const isExpanded = this.getAttribute('aria-expanded') === 'true';
            this.setAttribute('aria-expanded', !isExpanded);
            userMenu.classList.toggle('hidden');
        });
    </script>
</body>
</html>