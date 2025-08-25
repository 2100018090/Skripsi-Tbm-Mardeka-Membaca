<div class="bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
  <div class="max-w-7xl mx-auto">
    <h2 class="text-3xl font-bold text-teal-700 mb-10 text-center">Berita & Artikel</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <!-- Artikel 1 -->
      <div class="bg-white rounded-2xl shadow hover:shadow-lg transition overflow-hidden">
        <img src="https://source.unsplash.com/400x200/?storytelling,children" class="w-full h-40 object-cover" />
        <div class="p-5">
          <h3 class="text-lg font-bold text-gray-800">Malam Dongeng Nusantara</h3>
          <p class="text-sm text-gray-500 mb-2">10 Mei 2025</p>
          <p class="text-sm text-gray-700 mb-3">
            TBM Mardeka Membaca sukses mengadakan malam dongeng bersama komunitas lokal...
          </p>
          <button onclick="toggleDetail('artikel1')" class="text-teal-600 text-sm font-medium hover:underline">
            Baca Selengkapnya →
          </button>

          <div id="artikel1" class="mt-3 hidden text-sm text-gray-700">
            <p>Acara ini dihadiri lebih dari 50 anak. Dongeng dari berbagai daerah dibawakan oleh pendongeng profesional dan relawan komunitas literasi.</p>
            <button onclick="toggleDetail('artikel1')" class="text-sm text-teal-500 mt-2 hover:underline">Tutup</button>
          </div>
        </div>
      </div>

      <!-- Artikel 2 -->
      <div class="bg-white rounded-2xl shadow hover:shadow-lg transition overflow-hidden">
        <img src="https://source.unsplash.com/400x200/?book,reading" class="w-full h-40 object-cover" />
        <div class="p-5">
          <h3 class="text-lg font-bold text-gray-800">Pentingnya Buku Fisik</h3>
          <p class="text-sm text-gray-500 mb-2">9 Mei 2025</p>
          <p class="text-sm text-gray-700 mb-3">
            Meski era digital berkembang, buku fisik tetap penting untuk konsentrasi dan kenyamanan membaca...
          </p>
          <button onclick="toggleDetail('artikel2')" class="text-teal-600 text-sm font-medium hover:underline">
            Baca Selengkapnya →
          </button>

          <div id="artikel2" class="mt-3 hidden text-sm text-gray-700">
            <p>Buku fisik membantu pembaca terhindar dari gangguan notifikasi dan meningkatkan pemahaman dalam membaca panjang.</p>
            <button onclick="toggleDetail('artikel2')" class="text-sm text-teal-500 mt-2 hover:underline">Tutup</button>
          </div>
        </div>
      </div>

      <!-- Artikel 3 -->
      <div class="bg-white rounded-2xl shadow hover:shadow-lg transition overflow-hidden">
        <img src="https://source.unsplash.com/400x200/?community,event" class="w-full h-40 object-cover" />
        <div class="p-5">
          <h3 class="text-lg font-bold text-gray-800">Bazar Buku Gratis</h3>
          <p class="text-sm text-gray-500 mb-2">7 Mei 2025</p>
          <p class="text-sm text-gray-700 mb-3">
            Ratusan buku dibagikan gratis kepada masyarakat oleh relawan dan donatur TBM...
          </p>
          <button onclick="toggleDetail('artikel3')" class="text-teal-600 text-sm font-medium hover:underline">
            Baca Selengkapnya →
          </button>

          <div id="artikel3" class="mt-3 hidden text-sm text-gray-700">
            <p>Kegiatan ini bertujuan menumbuhkan minat baca dan menjangkau warga yang tidak mampu membeli buku.</p>
            <button onclick="toggleDetail('artikel3')" class="text-sm text-teal-500 mt-2 hover:underline">Tutup</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  function toggleDetail(id) {
    const el = document.getElementById(id);
    el.classList.toggle('hidden');
  }
</script>
