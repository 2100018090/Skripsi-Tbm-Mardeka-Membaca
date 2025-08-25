<footer class="bg-white pt-8 pb-6 mt-12">
    <div class="container mx-auto px-4">
      <!-- Flex berubah jadi flex-col di mobile -->
      <div class="flex flex-col lg:flex-row text-left lg:text-left">
        
        <!-- Bagian kiri (Logo dan Sosial Media) -->
        <div class="w-full lg:w-6/12 px-4 mb-6 lg:mb-0">
          <div class="flex items-center mb-4 bg-white justify-center lg:justify-start">
            <img src="{{ asset('storage/icon/logo.svg') }}" alt="Kontak Logo" class="w-42 h-42 mr-3">
          </div>
          <div class="mt-6 flex justify-center lg:justify-start flex-wrap">
            <button class="bg-white shadow-lg font-normal h-10 w-10 flex items-center justify-center rounded-full outline-none focus:outline-none mr-2 mb-2" type="button">
              <img src="{{ asset('storage/icon/logo_x.svg') }}" alt="Twitter" class="w-6 h-6" />
            </button>
            <button class="bg-white shadow-lg font-normal h-10 w-10 flex items-center justify-center rounded-full outline-none focus:outline-none mr-2 mb-2" type="button">
              <img src="{{ asset('storage/icon/logo_facebook.svg') }}" alt="Facebook" class="w-6 h-6" />
            </button>
            <button class="bg-white shadow-lg font-normal h-10 w-10 flex items-center justify-center rounded-full outline-none focus:outline-none mr-2 mb-2" type="button">
              <img src="{{ asset('storage/icon/logo_email.svg') }}" alt="Email" class="w-6 h-6" />
            </button>
            <button class="bg-white shadow-lg font-normal h-10 w-10 flex items-center justify-center rounded-full outline-none focus:outline-none mr-2 mb-2" type="button">
              <img src="{{ asset('storage/icon/logo_ig.svg') }}" alt="Instagram" class="w-6 h-6" />
            </button>
          </div>
        </div>
  
        <!-- Bagian kanan (Link menu dan media) -->
        <div class="w-full lg:w-6/12 px-4">
          <div class="flex flex-col lg:flex-row items-start lg:items-top mb-6">
            <div class="w-full lg:w-4/12 px-4 lg:ml-auto mb-4 lg:mb-0">
              <span class="block uppercase text-[#64C0B7] text-sm font-semibold mb-2">Menu Link</span>
              <ul class="list-unstyled">
                <li><a class="text-[#64C0B7] hover:text-blueGray-800 font-semibold block pb-2 text-sm" href="#beranda">Beranda</a></li>
                <li><a class="text-[#64C0B7] hover:text-blueGray-800 font-semibold block pb-2 text-sm" href="#tentang">Tentang</a></li>
                <li><a class="text-[#64C0B7] hover:text-blueGray-800 font-semibold block pb-2 text-sm" href="#buku populer">Buku Populer</a></li>
                <li><a class="text-[#64C0B7] hover:text-blueGray-800 font-semibold block pb-2 text-sm" href="#pengurus">Pengurus</a></li>
              </ul>
            </div>
  
            <div class="w-full lg:w-4/12 px-4">
              <span class="block uppercase text-[#64C0B7] text-sm font-semibold mb-2">Media Social</span>
              <ul class="list-unstyled">
                <li><a class="text-[#64C0B7] hover:text-blueGray-800 font-semibold block pb-2 text-sm" href="#">Twitter</a></li>
                <li><a class="text-[#64C0B7] hover:text-blueGray-800 font-semibold block pb-2 text-sm" href="#">Facebook</a></li>
                <li><a class="text-[#64C0B7] hover:text-blueGray-800 font-semibold block pb-2 text-sm" href="#">Email</a></li>
                <li><a class="text-[#64C0B7] hover:text-blueGray-800 font-semibold block pb-2 text-sm" href="#">Instagram</a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
  
      <hr class="my-6 border-blueGray-300" />
  
      <div class="flex flex-col md:flex-row items-center md:justify-between justify-center">
        <div class="w-full md:w-4/12 px-4 mx-auto text-center">
          <div class="text-sm text-blueGray-500 font-semibold py-1">
            MardekaLiterasi <span id="get-current-year">2025</span>
            <a href="#" class="text-blueGray-500 hover:text-gray-800">Your Company</a>.
          </div>
        </div>
      </div>
    </div>
  </footer>
  