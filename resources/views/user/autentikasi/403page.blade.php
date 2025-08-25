<!-- resources/views/errors/403.blade.php  (contoh) -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Not Authorized | 403</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen flex flex-col justify-center items-center bg-gray-50 text-gray-800 p-4">

  <!-- ILUSTRASI -->
  <div class="w-full max-w-md md:max-w-xl lg:max-w-2xl">

    <img src="/storage/img/403page.jpg" alt="Ilustrasi" class="w-full h-auto" />

  </div>

  <!-- TEKS & CTA -->
  <div class="mt-8 text-center space-y-4">
    <h1 class="text-2xl md:text-3xl font-semibold">Anda tidak berwenang</h1>
    <p class="text-base md:text-lg max-w-md mx-auto">
        Anda mencoba mengakses halaman yang tidak memiliki izin untuk melihatnya.
    </p>

    <a href="{{ route('landingpage') }}"
       class="inline-block mt-2 px-6 py-2 rounded-md text-white bg-[#3B82F6] hover:bg-blue-600 transition">
       ← Kembali
    </a>
  </div>

</body>
</html>
