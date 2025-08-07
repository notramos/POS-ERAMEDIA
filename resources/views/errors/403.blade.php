<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akses Tidak Diizinkan - 401</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
    </style>
</head>

<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8 text-center">
            <div>
                {{-- Error Icon --}}
                <div class="mx-auto flex items-center justify-center h-24 w-24 rounded-full bg-red-100 mb-6">
                    <svg class="h-12 w-12 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728" />
                    </svg>
                </div>

                {{-- Error Code --}}
                <h1 class="text-6xl font-bold text-gray-900 mb-2">401</h1>

                {{-- Error Title --}}
                <h2 class="text-2xl font-semibold text-gray-700 mb-4">Akses Tidak Diizinkan</h2>

                {{-- Error Description --}}
                <p class="text-gray-500 mb-8">
                    Maaf, Anda tidak memiliki izin untuk mengakses halaman ini.
                    Silakan login terlebih dahulu atau hubungi administrator.
                </p>

                {{-- Action Button --}}
                <div class="flex justify-center">
                    <button onclick="goBack()"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-8 rounded-lg transition duration-200 ease-in-out transform hover:scale-105 focus:outline-none focus:ring-4 focus:ring-blue-300">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali ke Halaman Sebelumnya
                    </button>
                </div>

                {{-- Additional Info --}}
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <p class="text-sm text-gray-400">
                        Error Code: 401 | <span id="timestamp-401"></span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function goBack() {
            if (window.history.length > 1) {
                window.history.back();
            } else {
                // Jika tidak ada history, redirect ke root
                window.location.href = '/';
            }
        }

        // Display current timestamp
        document.getElementById('timestamp-401').textContent = new Date().toLocaleString('id-ID');

        // Auto redirect after 10 seconds (optional)
        // setTimeout(goBack, 10000);
    </script>
</body>

</html>

{{-- ================================================================= --}}

{{-- resources/views/errors/404.blade.php --}}
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Tidak Ditemukan - 404</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .floating-animation {
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-10px);
            }
        }
    </style>
</head>

<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8 text-center">
            <div>
                {{-- Error Illustration --}}
                <div class="mx-auto mb-8 floating-animation">
                    <div
                        class="w-32 h-32 mx-auto bg-gradient-to-br from-blue-400 to-purple-500 rounded-full flex items-center justify-center">
                        <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                </div>

                {{-- Error Code --}}
                <h1 class="text-6xl font-bold text-gray-900 mb-2">404</h1>

                {{-- Error Title --}}
                <h2 class="text-2xl font-semibold text-gray-700 mb-4">Halaman Tidak Ditemukan</h2>

                {{-- Error Description --}}
                <p class="text-gray-500 mb-8">
                    Ups! Halaman yang Anda cari tidak dapat ditemukan.
                    Mungkin halaman telah dipindahkan atau dihapus.
                </p>

                {{-- Action Buttons --}}
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <button onclick="goBack()"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-lg transition duration-200 ease-in-out transform hover:scale-105 focus:outline-none focus:ring-4 focus:ring-blue-300">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali
                    </button>

                    <button onclick="goHome()"
                        class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-3 px-6 rounded-lg transition duration-200 ease-in-out transform hover:scale-105 focus:outline-none focus:ring-4 focus:ring-gray-300">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Beranda
                    </button>
                </div>

                {{-- Try Again Button --}}
                <div class="mt-6">
                    <button onclick="retryPage()"
                        class="text-blue-600 hover:text-blue-800 text-sm underline focus:outline-none">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Coba Lagi
                    </button>
                </div>

                {{-- Footer Info --}}
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <p class="text-sm text-gray-400">
                        Error Code: 404 | <span id="timestamp-404"></span>
                    </p>
                    <p class="text-xs text-gray-300 mt-2">
                        URL: <span id="current-url"></span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function goBack() {
            if (window.history.length > 1) {
                window.history.back();
            } else {
                goHome();
            }
        }

        function goHome() {
            window.location.href = '/';
        }

        function retryPage() {
            window.location.reload();
        }

        // Display current info
        document.getElementById('timestamp-404').textContent = new Date().toLocaleString('id-ID');
        document.getElementById('current-url').textContent = window.location.pathname;

        // Optional: Auto redirect after 15 seconds
        // let countdown = 15;
        // const timer = setInterval(() => {
        //     countdown--;
        //     if (countdown <= 0) {
        //         clearInterval(timer);
        //         goBack();
        //     }
        // }, 1000);
    </script>
</body>

</html>
