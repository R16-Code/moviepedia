<?php
// about.php
include 'config/db_koneksi.php';
include 'config/functions.php'; 
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang Kami - Movie.pedia</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-black text-white font-sans min-h-screen">

    <nav class="bg-black shadow-lg w-full sticky top-0 z-50 border-b border-red-600">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <a href="index.php" class="text-2xl font-bold text-red-600">Movie.pedia</a>
            <div class="flex items-center space-x-4">
                <a href="index.php" class="text-white hidden hover:text-orange-500 hidden md:block">Beranda</a>
                <a href="about.php" class="text-red-600 font-medium hover:text-orange-500 hidden md:block">Tentang Kami</a>
                <a href="contact.php" class="text-white hover:text-orange-500 hidden md:block">Kontak</a>           
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="user/watchlist.php" class="text-white hover:text-orange-500 hidden md:block">Watchlist Saya</a>
                    <?php if ($_SESSION['role'] === 'admin'): ?>
                        <a href="admin/dashboard.php" class="text-white hover:text-orange-500 hidden md:block">Admin</a>
                    <?php endif; ?>
                    <span class="text-red-600 hidden md:block">|</span>
                    <span class="font-medium">Halo, <?= htmlspecialchars($_SESSION['username']); ?></span>
                    <a href="auth/logout.php" class="bg-red-600 hover:bg-orange-600 text-white px-3 py-1 rounded-md text-sm font-medium">
                        Logout
                    </a>
                <?php else: ?>
                    <span class="text-red-600 hidden md:block">|</span>
                    <a href="auth/login.php" class="text-white hover:text-orange-500">Login</a>
                    <a href="auth/register.php" class="bg-red-600 hover:bg-orange-600 text-white px-3 py-1 rounded-md text-sm font-medium">
                        Register
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="container mx-auto p-4 md:p-16">
        <div class="max-w-4xl mx-auto">

            <div class="text-center mb-12">
                <h1 class="text-4xl md:text-5xl font-extrabold text-red-600 mb-4">Tentang Movie.pedia</h1>
                <p class="text-lg text-gray-400 max-w-2xl mx-auto">
                    Platform katalog film tempat kamu bisa menjelajahi berbagai judul film dari seluruh dunia. Pengguna dapat menelusuri film yang diinginkan, memfilter berdasarkan genre, serta menambahkan film favorit ke watchlist untuk ditonton nanti. Semua disajikan dalam tampilan modern dan dark mode yang nyaman di mata.
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
                
                <div>
                    <h2 class="text-3xl font-semibold text-white mb-4">Visi & Misi</h2>
                    
                    <div class="space-y-4 text-gray-300">
                        <p class="flex items-start">
                            <span class="text-red-600 mr-2 mt-1">&#10003;</span> 
                            <span class="font-bold text-white mr-1">Visi:</span> 
                            Menjadi platform referensi terpercaya bagi pecinta film untuk menemukan dan mengelola daftar tontonan mereka dengan mudah.
                        </p>
                        <p class="flex items-start">
                            <span class="text-red-600 mr-2 mt-1">&#10003;</span> 
                            <span class="font-bold text-white mr-1">Misi:</span> 
                            Menyediakan katalog film yang selalu up-to-date, dilengkapi fitur pencarian dan filter genre, serta watchlist pribadi dengan antarmuka yang sederhana namun menarik.
                        </p>
                    </div>
                </div>

                <div class="bg-red-900 p-6 rounded-lg border border-red-600 shadow-lg">
                    <h3 class="text-xl font-semibold text-white mb-3">Mengapa Movie.pedia Hadir?</h3>
                    <p class="text-gray-300">
                        Movie.pedia lahir dari keinginan untuk menghadirkan tempat eksplorasi film yang praktis dan menyenangkan. Kami ingin membantu pengguna menemukan film yang sesuai selera tanpa ribet — cukup cari, pilih, dan tambahkan ke watchlist.
                    </p>
                </div>
            </div>

            <h2 class="text-3xl text-center font-semibold text-white mb-3 pt-4 border-t border-gray-800">Tim & Kontributor</h2>
            <p class="text-gray-400 text-center mb-6">Inisiator dan kontributor utama dalam pengembangan database dan fitur Movie.pedia.</p>

            <div class="grid grid-cols-1 place-items-center">
                <div class="bg-red-800 p-5 rounded-lg border border-red-700 flex items-center space-x-4 w-fit">
                    <span class="text-4xl text-red-400">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
                    </span>
                    <div class="">
                        <h4 class="text-xl font-bold text-white">Ridho Nur Maulana</h4>
                        <p class="text-red-300">Fullstack Developer</p>
                        <p class="text-sm text-orange-400">ridhonurmaulana25@gmail.com</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>