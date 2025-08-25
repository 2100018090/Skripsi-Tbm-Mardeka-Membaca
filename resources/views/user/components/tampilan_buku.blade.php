@extends('user.layouts.default.beranda')

@section('content')

{{-- ================== Wrapper Fullscreen ================== --}}
<div id="reader-wrapper" class="overflow-hidden bg-white h-screen flex flex-col">

    {{-- Header: Tombol kembali & judul --}}
    <div class="flex items-center justify-between px-4 py-3 border-b border-gray-200 text-sm">
        <a href="{{ url()->previous() }}" class="text-[#64C0B7] hover:underline">← Kembali</a>
        <h1 class="font-semibold text-[#333] truncate">
            📖 <span class="text-[#64C0B7]">{{ $buku->judul }}</span>
        </h1>
    </div>

    {{-- Flip-book (auto height 70-75%) --}}
    <div id="flip-frame"
         class="mx-auto my-2 flex-shrink-0 rounded-xl border shadow-md bg-gray-100 overflow-hidden relative"
         style="max-height: 75vh;">

        <div id="flipbook" class="m-auto cursor-pointer select-none">
            @foreach($halaman as $idx => $img)
                <div>
                    <img src="{{ $img }}" alt="Halaman {{ $idx+1 }}"
                         class="w-full h-full object-contain"/>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Info Buku (paling bawah) --}}
    <div class="px-4 py-2 text-xs text-gray-600 bg-white overflow-y-auto"
         style="flex: 1 1 auto; max-height: 25vh;">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-1">
            <p><strong>📌 Penulis:</strong> {{ $buku->penulis }}</p>
            <p><strong>🏢 Penerbit:</strong> {{ $buku->penerbit }}</p>
            <p><strong>📅 Tahun Terbit:</strong> {{ \Carbon\Carbon::parse($buku->tahun_terbit)->format('Y') }}</p>
            <p><strong>💬 Deskripsi:</strong> {{ $buku->deskripsi }}</p>
        </div>
    </div>

</div>

{{-- ====================== SCRIPTS ====================== --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/turn.js/3/turn.min.js"></script>

<script>
(function () {
    const NAV_HEIGHT = document.querySelector('nav')?.offsetHeight || 64;
    const DESKTOP_W = 800, DESKTOP_H = 500;
    const MOBILE_RATIO = 1.5;

    function resizeReader() {
        const vw = window.innerWidth;
        const vh = window.innerHeight;

        const frame = document.getElementById('flip-frame');
        const flip = $('#flipbook');

        if (vw >= 768) {
            frame.style.width = (DESKTOP_W + 20) + 'px';
            frame.style.height = (DESKTOP_H + 20) + 'px';
            flip.width(DESKTOP_W).height(DESKTOP_H);
            flip.turn('display', 'double');
        } else {
            const w = Math.min(vw - 32, 360);
            const h = w * MOBILE_RATIO;
            frame.style.width = (w + 20) + 'px';
            frame.style.height = (h + 20) + 'px';
            flip.width(w).height(h);
            flip.turn('display', 'single');
        }

        document.getElementById('reader-wrapper').style.height = vh + 'px';
    }

    document.addEventListener('DOMContentLoaded', () => {
        $('#flipbook').turn({
            autoCenter: true,
            elevation: 50,
            gradients: true,
            acceleration: true,
            duration: 800
        });

        resizeReader();
        window.addEventListener('resize', resizeReader);

        $('#flipbook').on('click', function (e) {
            const half = this.offsetWidth / 2;
            const xPos = e.pageX - this.getBoundingClientRect().left;
            $(this).turn(xPos < half ? 'previous' : 'next');
        });
    });
})();
</script>
@endsection
